<?php
include_once "../include/mypaths.php";
spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$conn = Singleton::getInstance();

spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$response=array("error"=>true);
if(!$conn){
    $response['errmsg'] = "Unable to connect to database";    
}elseif(isset($_POST['amount'])){

}else{
    $pk = Returns::getAll($_SESSION['myfile']);
}

echo json_encode($response);
