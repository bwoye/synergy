<?php

session_start();

include_once "../php/classes/datatables.php";
include_once "../include/connector.php";

$conn = Singleton::getInstance();

$response = array('error' => true);
$response['errmsg'] = "Invalid user  or password";


if (isset($_POST['submit'])) { 
    //Find out if user is Admin or Grantee
  
    if($_POST['utype'] == 'admin'){
        //Login as synergy employee
        //First find out if users exists
        $sql = $conn->run("SELECT * FROM ".TBL_USERS. " WHERE userid=:userid",["userid"=>$_POST['userid']]);

        if($sql->rowCount() != 1){
            header("location: ../index.php?invalidUser=true");
            exit();
        }else{
            //Verify password of user
            $mypass = $sql->fetch();
            if(password_verify($_POST['kpass'],$mypass->kpass)){
                $_SESSION['userid'] = $mypass->userid;
                $_SESSION['fulname'] =$mypass->fulname;
                $_SESSION['kpass'] = $_POST['kpass'];
                $_SESSION['utype'] = $mypass->utype;
                $_SESSION['tzone'] = $_POST['tzone'];
        
                date_default_timezone_set('UTC');
                $given = new DateTime();
                $takes = $given->format('Y-m-d H:i:s');
        
                $conn->run("INSERT INTO ".TBL_TRANS." VALUES(?,?,?,?)", [null, $_SESSION['fulname'], "Logged in", $takes]);
                header("location: ../entities.html");                
            }else{
                header("location: ../index.php?invalidUser=true");
                exit();
            }
        }
    }else{
        //Login as Grantee for entering records
        //find out if file exists
        $sql = $conn->run("SELECT emppass,Granteename FROM ".TBL_ENTITIES." WHERE fileno=:fileno",["fileno"=>$_POST['userid']]);
        if($sql->rowCount() == 1){
            $mypass = $sql->fetch();
            if(password_verify($_POST['kpass'],$mypass->emppass)){
                $_SESSION['myfile'] = $_POST['userid'];
                $_SESSION['userid'] = "grantee";
                $_SESSION['Grantee'] = $mypass->Granteename;
                $_SESSION['kpass'] = $_POST['kpass'];
                header("location: ../grantee.html");

                // $response = array("error"=>false);
                // $response['login'] = "glogin";
                // echo json_encode($response);
            }else{
                header("location: ../index.php?invalidGrantee=true");
                exit();
            }
        }else{
            header("location: ../index.php?invalidGrantee=true");
            exit();
        }
    }
}else{
    header("location: ../index.php");
}
