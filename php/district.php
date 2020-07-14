<?php
//session_start();
include_once "../include/mypaths.php";

spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$conn = Singleton::getInstance();

$response = array('error'=>true);
if(!$conn){
    $response['errmsg'] = "Unable to log into databases";
}else if(isset($_POST['editpal'])){
    $region = $_POST['region'];
    $Districtname=$_POST['Districtname'];
    $Districtcode = $_POST['Districtcode'];

    $conn->run("UPDATE districts SET region=:region,Districtname=:Districtname WHERE Districtcode=:Districtcode",['Districtcode'=>$Districtcode,'region'=>$region,'Districtname'=>$Districtname]);

    $response['Districtname'] = $Districtname;
    $response['region'] = $region;
    $response['Districtcode'] = $Districtcode;
}else if(isset($_POST['adding'])){
    $Districtname = $_POST['Districtname'];
    $region = $_POST['region'];

    try{
        $conn->beginTransaction();
        $conn->run("INSERT INTO districts VALUES(?,?,?)",[NULL,$Districtname,$region]);
        $response['Districtcode'] = $conn->userInsert();
        $conn->commit();

        $bb = $conn->run("SELECT COUNT(*) FROM districts");
        $kk = $bb->fetch(PDO::FETCH_NUM);
        $response['counts'] = $kk[0];

        $response['Districtname'] = $Districtname;
        $response['region'] = $region;
    }catch(PDOException $e){
        $conn->rollBack();
        $response['error'] = true;
        $response['errmsg']="Record was not inserted";
    }
}else if(isset($_POST['delete'])){
    $conn->run("DELETE FROM districts WHERE Districtcode=?",[$_POST['delete']]);
    
    $bb = $conn->run("SELECT COUNT(*) FROM districts");
    $kk = $bb->fetch(PDO::FETCH_NUM);
    $response['counts'] = $kk[0];
}else{
    $pk = $conn->run("SELECT a.*,b.description FROM ".TBL_DISTRICTS." a LEFT JOIN ".TBL_REGIONS." b USING(Region) ORDER BY a.Districtname");
    for($m=0;$v=$pk->fetch(PDO::FETCH_ASSOC);$m++){
        $response['districts'][] = $v;
    }

    $cc = $conn->run("SELECT perms FROM userperm WHERE userid=? AND mypage=?", [$_SESSION['userid'], 'district']);

    $j = $cc->fetch();
    $response['perms'] = $j->perms;
}
echo json_encode($response);