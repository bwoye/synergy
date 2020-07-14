<?php

session_start();

include_once "../php/classes/datatables.php";
include_once "../include/connector.php";


$conn = Singleton::getInstance();


date_default_timezone_set('UTC');
$given = new DateTime();
$takes = $given->format('Y-m-d H:i:s');

$conn->run("INSERT INTO ".TBL_TRANS. " VALUES(?,?,?,?)", [null, $_SESSION['fulname'], "Logged out", $takes]);


session_unset();
session_destroy();
header("location: ../index.php");
