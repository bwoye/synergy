<?php
//session_start();
include "../include/mypaths.php";
spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$response = array("error" => true);
if (isset($_POST['pkword1'])) {
    //$pkwordoriginal = $_POST['pkwordoriginal'];
    $pkword1 = $_POST['pkword1'];
    $original = $_POST['original'];
    if (empty($pkword1) || empty($original)) {
        $response['errmsg'] = "Unable to set password";
        echo json_encode($response);
        exit();
    } else {
        if (trim($original) == trim($_SESSION['kpass'])) {
            $pp = Entity::getMember($_SESSION['myfile']);

            $vk = $pp->setNewPassword($pkword1);
            if ($vk == true) {
                $pp->setNewPassword($pkword1);
                $response['error'] = false;
                $response['errmsg'] = "password was successfully updated";
            } else {
                $response['errmsg'] = "password was not updated";
            }
            echo json_encode($response);
        } else {
            $response['errmsg'] = "Unable to change password";
            echo json_encode($response);
            exit();
        }
    }
} else {
    header("location: ../index.php");
    exit();
}
