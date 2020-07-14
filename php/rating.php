<?php
include_once "../include/mypaths.php";
spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$conn = Singleton::getInstance();

$response = array('error'=>true);
if(!$conn){
    $response['errmsg'] = "Unable to log into databases";
}else if(isset($_POST['baseline'])){
    $fileno = $_POST['baseline'];
    $mg = $conn->run("SELECT * FROM skilling WHERE fileno=? && sampled=?",[$fileno,1]);
    $response['counts']= $mg->rowCount();
    $response['fileno'] = $fileno;
    for($j=0;$mk=$mg->fetch();$j++){
        $response['sample'][$j]['fulname']=$mk->fulname;
        $response['sample'][$j]['recno']=$mk->recno;
        $response['sample'][$j]['bknowtool']=$mk->bknowtool;
        //$response['sample'][$j]['bknowtooltext']=$mk->fulname;
        $response['sample'][$j]['busetool']=$mk->busetool;
        //$response['sample'][$j]['busetooltext']=$mk->busetool;
        $response['sample'][$j]['bapptool']=$mk->bapptool;
        //$response['sample'][$j]['bapptooltext']=$mk->fulname;
    }
}else if(isset($_POST['midline'])){
    $fileno = $_POST['midline'];
    $mg = $conn->run("SELECT * FROM skilling WHERE fileno=? && sampled=?",[$fileno,1]);
    $response['counts']= $mg->rowCount();
    $response['fileno'] = $fileno;

    for($j=0;$mk=$mg->fetch();$j++){
        $response['sample'][$j]['fulname']=$mk->fulname;
        $response['sample'][$j]['recno']=$mk->recno;
        $response['sample'][$j]['bknowtool']=$mk->mknowtool;
        //$response['sample'][$j]['bknowtooltext']=$mk->fulname;
        $response['sample'][$j]['busetool']=$mk->musetool;
        //$response['sample'][$j]['busetooltext']=$mk->busetool;
        $response['sample'][$j]['bapptool']=$mk->mapptool;
        //$response['sample'][$j]['bapptooltext']=$mk->fulname;
    }

}else if(isset($_POST['endline'])){
    $fileno = $_POST['endline'];
    $mg = $conn->run("SELECT * FROM skilling WHERE fileno=? && sampled=?",[$fileno,1]);
    $response['counts']= $mg->rowCount();
    $response['fileno'] = $fileno;

    for($j=0;$mk=$mg->fetch();$j++){
        $response['sample'][$j]['fulname']=$mk->fulname;
        $response['sample'][$j]['recno']=$mk->recno;
        $response['sample'][$j]['bknowtool']=$mk->eknowtool;
        //$response['sample'][$j]['bknowtooltext']=$mk->fulname;
        $response['sample'][$j]['busetool']=$mk->eusetool;
        //$response['sample'][$j]['busetooltext']=$mk->busetool;
        $response['sample'][$j]['bapptool']=$mk->eapptool;
        //$response['sample'][$j]['bapptooltext']=$mk->fulname;
    }
}else if(isset($_POST['baselineup'])){
    $recno = $_POST['baselineup'];
    $bline = $_POST['tots'];
    $rline = $_POST['myline'];
    $gval = $_POST['selval'];

    try{
        $conn->beginTransaction();
        $mk = $conn->run("UPDATE skilling SET bline=? WHERE recno=?",[$bline,$recno]);

        if($rline == "know"){
            $conn->run("UPDATE skilling SET bknowtool=? WHERE recno=?",[$gval,$recno]);
        }else if($rline == 'expl'){
            $conn->run("UPDATE skilling SET busetool=? WHERE recno=?",[$gval,$recno]);
        }else{
            $conn->run("UPDATE skilling SET bapptool=? WHERE recno=?",[$gval,$recno]);
        }

        $conn->commit();

    }catch(PDOException $e){
        $conn->rollBack();
    }
   

    $knarray = array();
    $pf = $conn->run("SELECT * FROM skillvalues");
    while($kv = $pf->fetch(PDO::FETCH_NUM)){
        $knarray[$kv[0]] = $kv[1];
    }

    //print_r($knarray);

    $response['recno'] = $recno;
    $response['tots'] = $bline;
    $response['texttot'] = $knarray[$bline];
}else if(isset($_POST['midtermup'])){
    $recno = $_POST['midtermup'];
    $bline = $_POST['tots'];
    $rline = $_POST['myline'];
    $gval = $_POST['selval'];

    try{
        $conn->beginTransaction();
        $mk = $conn->run("UPDATE skilling SET mline=? WHERE recno=?",[$bline,$recno]);

        if($rline == "know"){
            $conn->run("UPDATE skilling SET mknowtool=? WHERE recno=?",[$gval,$recno]);
        }else if($rline == 'expl'){
            $conn->run("UPDATE skilling SET musetool=? WHERE recno=?",[$gval,$recno]);
        }else{
            $conn->run("UPDATE skilling SET mapptool=? WHERE recno=?",[$gval,$recno]);
        }

        $conn->commit();

    }catch(PDOException $e){
        $conn->rollBack();
    }
   

    $knarray = array();
    $pf = $conn->run("SELECT * FROM skillvalues");
    while($kv = $pf->fetch(PDO::FETCH_NUM)){
        $knarray[$kv[0]] = $kv[1];
    }

    //print_r($knarray);

    $response['recno'] = $recno;
    $response['tots'] = $bline;
    $response['texttot'] = $knarray[$bline];
}else if(isset($_POST['endtermup'])){
    $recno = $_POST['endtermup'];
    $bline = $_POST['tots'];
    $rline = $_POST['myline'];
    $gval = $_POST['selval'];

    try{
        $conn->beginTransaction();
        $mk = $conn->run("UPDATE skilling SET eline=? WHERE recno=?",[$bline,$recno]);

        if($rline == "know"){
            $conn->run("UPDATE skilling SET eknowtool=? WHERE recno=?",[$gval,$recno]);
        }else if($rline == 'expl'){
            $conn->run("UPDATE skilling SET eusetool=? WHERE recno=?",[$gval,$recno]);
        }else{
            $conn->run("UPDATE skilling SET eapptool=? WHERE recno=?",[$gval,$recno]);
        }

        $conn->commit();

    }catch(PDOException $e){
        $conn->rollBack();
    }
   

    $knarray = array();
    $pf = $conn->run("SELECT * FROM skillvalues");
    while($kv = $pf->fetch(PDO::FETCH_NUM)){
        $knarray[$kv[0]] = $kv[1];
    }

    //print_r($knarray);

    $response['recno'] = $recno;
    $response['tots'] = $bline;
    $response['texttot'] = $knarray[$bline];
}else if(isset($_POST['selasses'])){

    $recno = $_POST['selasses'];
    $sampled = $_POST['sampled'];

    $conn->run("UPDATE skilling SET sampled=? WHERE recno=?",[$sampled,$recno]);

}else{

}
echo json_encode($response);