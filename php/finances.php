<?php
include_once "../include/mypaths.php";

spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});


$conn = Singleton::getInstance();

$response = array('error'=>true);
if(!$conn){
    $response['errmsg'] = "Unable to log into databases";
}else if(isset($_POST['fileno'])){
    $fileno = $_POST['fileno'];
    $ApprovedbudgetMlnUgx = $_POST['ApprovedbudgetMlnUgx'];
    $GranteecontrbnMlnUgx = $_POST['GranteecontrbnMlnUgx'];
    $startdate = $_POST['startdate'];
    $duration = $_POST['duration'];
    $dform = $_POST['dform'];
    $mydform = $_POST['mydform'];

    try{
        $conn->beginTransaction();
        $fg = $conn->run("SELECT fileno FROM approval WHERE fileno=?",[$fileno]);
        if($fg->rowCount() == 1){
            //Let us update an existing record
            $conn->run("UPDATE approval SET ApprovedbudgetMlnUgx=:ApprovedbudgetMlnUgx,GranteecontrbnMlnUgx=:GranteecontrbnMlnUgx,startdate=:startdate,duration=:duration,dform=:dform WHERE fileno=:fileno",['ApprovedbudgetMlnUgx'=>$ApprovedbudgetMlnUgx,'GranteecontrbnMlnUgx'=>$GranteecontrbnMlnUgx,'startdate'=>$startdate,'duration'=>$duration,'dform'=>$dform,'fileno'=>$fileno]);
        }else{
            //We create a new entry for this file
            $conn->run("INSERT INTO approval (recno,ApprovedbudgetMlnUgx,GranteecontrbnMlnUgx,startdate,fileno,duration,dform) VALUES(?,?,?,?,?,?,?)",[NULL,$ApprovedbudgetMlnUgx,$GranteecontrbnMlnUgx,$startdate,$fileno,$duration,$dform]);
        }

        $conn->commit();
    }catch(PDOException $e){
        $conn->rollBack();
        $response['errmsg']="Record failed to update";
    }

    
    
    $response['ApprovedbudgetMlnUgx']=$ApprovedbudgetMlnUgx;
    $response['GranteecontrbnMlnUgx']= $GranteecontrbnMlnUgx;
    $response['startdate']=$startdate;
    $response['duration']=$duration;
    $response['dform']=$dform;
    $response['mydform']=$mydform;

}else if(isset($_POST['nupay'])){
    $fileno = $_POST['nupay'];

    $vv = $conn->run("SELECT * FROM payments WHERE fileno=? ORDER BY datepaid DESC",[$fileno]);

    for($j=0;$k=$vv->fetch();$j++){
        $response['payments'][$j]['datepaid']=$k->datepaid;
        $response['payments'][$j]['amount']=$k->amount;
        $response['payments'][$j]['ownamount']=$k->ownamount;
        $response['payments'][$j]['recno']=$k->recno;       
    }
}else if(isset($_POST['nupayfile'])){
    $fileno=$_POST['nupayfile'];
    $amount=$_POST['amount'];
    $ownamount=$_POST['ownamount'];
    $datepaid=$_POST['datepaid'];

    $last= 0;
    try{
        $conn->beginTransaction();
        $conn->run("INSERT INTO payments VALUES(?,?,?,?,?)",[NULL,$fileno,$amount,$datepaid,$ownamount]);
        $last = $conn->userInsert();
        $conn->commit();

        $vk = $conn->run("SELECT COUNT(*) FROM payments WHERE fileno=?",[$fileno]);

        $response['fileno'] = $fileno;
        $response['recno'] = $last;
        $response['amount'] = $amount;
        $response['ownamount'] = $ownamount;
        $response['datepaid'] = $datepaid;
        $response['counts'] = $vk->rowCount();

    }catch(PDOException $e){
        $conn->rollBack();
        $response['errmsg'] = "Record was not updated";
    }
}
echo json_encode($response);