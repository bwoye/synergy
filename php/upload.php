
<?php
/*
Script for uploading and renaming monthly reports
Author Samuel Bwoye
Date 29-August-2019
*/
session_start();
include_once "../include/mypaths.php";
//include_once "../php/makefilename.php";
include_once "../php/makefilefunctions.php";

//php\makefilename.php

if (!isset($_SESSION['myfile'])) {
    header("location: ../index.html");
    exit();
}

$conn = Singleton::getInstance();

if (!$conn) {
    $response['errmsg'] = "Unable to log into databases";
} else {
    if (isset($_POST['myupload'])) {

        //Array ( [name] => Logo.jpg [type] => image/jpeg [tmp_name] => C:\wamp\tmp\php5568.tmp [error] => 0 [size] => 478299 )
        if ($_FILES['myreport']['name'] != '' && $_POST['selmonth'] != '' && $_POST['curyear'] != '') {
            //print_r($_FILES['myreport']);
            $file = $_FILES['myreport'];
            $period = $_POST['selmonth'];
            $xyear = $_POST['curyear'];
            $fileExt = $file['type'];
            $tmpName = $file['tmp_name'];
            $allowed = array('xls', 'xlsx', 'doc', 'docx', 'pdf');

            $fileno = $_SESSION['myfile'];

            $ext = explode('.', $file['name']);
            $fileNew = '../monthlyreports/' . mkfilename($period, $fileno, $xyear) . "." . end($ext);

            if (file_exists($fileNew)) {
                echo "File already uploaded";
            } else if (in_array(end($ext), $allowed)) {
                move_uploaded_file($tmpName, $fileNew);
                header("Location: ../grantee.html?fileuploadsuccess");

                //Create array of months so that files can be arranged
               
                //not yet uhuru
            } else {
                echo "Error uploading file";
                header("location: ../grantee.html?fileuploaderroe");
            }

        } else {
            echo "Error uploading";
            header("location: ../grantee.html?fileuploaderroe");
        }
    }
}

function listReports()
{
    $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'november', 'december');

    $dirs = '../';
    echo "<ul>";
    if ($handle = opendir($dirs)) {
        while ($file = readdir($handle)) {
            // if ($file != '..' && $file != '.'  && rtrim()) {
            //     echo "<li>" . $file . "</li>";
            // }
        }
        closedir($handle);
    }
    echo "</ul>";
}
