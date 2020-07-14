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
}else if(isset($_POST['delete'])){
    $conn->run("DELETE FROM skillingareas WHERE skillcode=?",[$_POST['delete']]);
}else if(isset($_POST['updating'])){
    $conn->run("UPDATE skillingareas SET Skilldescription=? WHERE skillcode=?",[$_POST['Skilldescription'],$_POST['updating']]);
    $response['Skilldescription'] = $_POST['Skilldescription'];
    $response['skillcode'] = $_POST['updating'];
}else if(isset($_POST['adding'])){
    $Skilldescription = $_POST['Skilldescription'];

    try{
        $conn->beginTransaction();
        $conn->run("INSERT INTO skillingareas VALUES(?,?)",[NULL,$Skilldescription]);
        $response['skillcode'] = $conn->userInsert();
        $conn->commit();
        $response['Skilldescription'] = $Skilldescription;
    }catch(PDOException $e){
        $conn->rollBack();
    }
}else{
    $hj = $conn->run("SELECT * FROM skillingareas ORDER BY Skilldescription");

    for($mv=0;$mk=$hj->fetch();$mv++){
        $response['skills'][$mv]['skillcode'] = $mk->skillcode;
        $response['skills'][$mv]['Skilldescription'] = $mk->Skilldescription;
    }

    $response['counts'] = $hj->rowCount();

    $cc = $conn->run("SELECT perms FROM userperm WHERE userid=? AND mypage=?", [$_SESSION['userid'], 'skillarea']);

    $j = $cc->fetch();
    $response['perms'] = $j->perms;
}
echo json_encode($response);