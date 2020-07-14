<?php
include_once '../include/mypaths.php';
//echo "<br>".MYCLASSES;
spl_autoload_register(function ($class_name) {
    include MODELS.DS.$class_name . ".php";
});

$conn = Singleton::getInstance();

$response = array('error'=>true);
if(!$conn){
    $response['errmsg'] = "Unable to log into databases";
}else if(isset($_POST['fileno']) && !isset($_POST['adding'])){
    Payments::getPayments($_POST['fileno']);
}else if(isset($_POST['editing'])){
    $recno = $_POST['recno'];
    $amount = $_POST['amount'];
    $ownamount = $_POST['ownamount'];
    $datepaid = $_POST['datepaid'];

    unset($_POST['editing']);
    $newPay = new Payments($_POST);

    $old = PAyments::getOnePayment($_POST['recno']);

    if($old == $newPay){
        return false;
        exit();
    }  

    $gg = Payments::getOnePayment($recno);
    $myentity = Entity::getMember($gg->getValue("fileno"));    

    $newPay->updatePayments();

    $response['datepaid'] = $datepaid;
    $response['amount'] = $amount;
    $response['ownamount'] = $ownamount;
    $response['recno'] = $recno;

    $given = new DateTime();
    $takes = $given->format('Y-m-d H:i:s');

    $trans = new Translog(["recno"=>NULL,"fulname"=>$_SESSION['fulname'],"action"=>"Edited payment for ".$myentity->getValue("Granteename"),$takes]);

    $trans->addItem();


}else if(isset($_POST['mydel'])){


    $gg = Payments::getOnePayment($_POST['mydel']);
    $fileno = $gg->getValue("fileno");
    $myentity = Entity::getMember($fileno);    
    $given = new DateTime();
    $takes = $given->format('Y-m-d H:i:s');

    $pp = new Translog([NULL,$_SESSION['fulname'],'Deleted Payment for '.$myentity->getValue("Granteename"),$takes]);
    $pp->addItem();

}else if(isset($_POST['adding'])){    
  
    $jj = new Payments($_POST);
    $jj->addPayment();

}else{   
    $response['entities'] = Entity::getFullTable();
    echo json_encode($response);
}
