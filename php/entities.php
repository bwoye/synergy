<?php
include '../include/mypaths.php';
spl_autoload_register(function ($class_name) {
    include_once MODELS.DS.$class_name . ".php";
});

$conn = Singleton::getInstance();

$response = array('error' => true);
if (!$conn) {
    $response['errmsg'] = "Unable to log into databases";
} else if (isset($_POST['adding'])) {
    $jim = new Entity($_POST);
    // print_r($jim);
    // exit();
    $jim->saveRecord();
   
} else if (isset($_POST['deleting'])) {
    $myarray = array("fileno"=>$_POST['deleting']);
    $mk = new Entity($myarray);
    $mk->delete();    
} else if (isset($_POST['updating'])) {  
    //print_r($_POST);
    $bwoye = new Entity($_POST);      
    //print_r($bwoye);
    //exit();
    $bwoye->SaveEdits($_POST['oldfile']);
} else {
    $response = Entity::showAll();
    //header("Content-Type:Application/json");    
}
echo json_encode($response);
