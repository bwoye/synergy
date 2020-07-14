<?php
/*
Script for displaying uploaded reports to grantee
Author Samuel Bwoye
Date 29-August-2019
*/

session_start();
include '../php/makefilefunctions.php';
if(!isset($_SESSION['myfile'])){
    header("location: ../index.html");
    exit;
}
$mynumber = $_SESSION['myfile'];
$dirs = '../monthlyreports';
$fileArray = array();
$mp = 0;
if ($handle = opendir($dirs)) {
    while ($file = readdir($handle)) {
        if ($file != '..' && $file != '.') {

            //remove the dot form the file name
            $mv = explode('.',$file);

            //find the year from separated file name
            preg_match('/\d\d\d\d/', $mv[0], $matches);
            
            //separate filename and number using the year
            $xx = explode($matches[0], $mv[0]);
            if (mynewfile($_SESSION['myfile']) != $xx[1]) {
                continue;
            }

            $fileArray[] = $file;
            $mp += 1;
        }
    }
    closedir($handle);
}

$k = 0;
$mylist = array();
foreach ($fileArray as $mk) {

    $mylist[] = getfilenoFromreturn($mk);
    $k += 1;
}


$flist = sortArray($mylist, 'forsort');

// http://localhost/synergy/php/makefilename.php
$x = 0;
$response = array();
foreach ($flist as $mk => $v) {
    $response['meets'][$x]['filename'] = $v['filename'];
    $response['meets'][$x]['Month'] = $v['month'];
    $response['meets'][$x]['Year'] = $v['Year'];
    $x += 1;
}

echo json_encode($response);

// function sortArray($myr, $mkey)
// {
//     $b = array();
//     foreach ($myr as $k => $v) {
//         $b[] = $v[$mkey];
//     }
//     asort($b);

//     $mylist2 = array();
//     foreach ($b as $v => $k) {
//         $mylist2[] = $myr[$v];
//     }
//     return $mylist2;
// }



// function mkfilename($period, $fileno, $xyear)
// {
//     mynewfile($fileno);
//     return $period . $xyear . $clfile;
// }

// function getfilenoFromreturn($filename)
// {
//     //Explode the filename
//     $myreturn = array();

//     $months = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'november', 'december');
//     $gotfile = explode('.', $filename);

//     //Get the file without extension from the array above
//     $usefile = $gotfile[0];

//     //Get the year from the array
//     preg_match('/\d\d\d\d/', $usefile, $matches);
//     $myreturn['filename'] = $filename;
//     $myreturn['Year'] = $matches[0];

//     //Get the name of the month
//     $dmon = explode($matches[0], $usefile);
//     $myreturn['month'] = $dmon[0];
//     $tt = 1;
//     foreach ($months as $fk) {
//         if ($fk == $dmon[0]) {
//             $myreturn['monthnum'] = $tt;
//             $myreturn['forsort'] = $myreturn['Year'] . $tt;
//             break;
//         }
//         $tt += 1;
//     }
//     return $myreturn;
// }

// function mynewfile($fileno)
// {
//     $clfile = str_replace('/', '', $fileno);
//     $clfile = str_replace('-', '', $clfile);

//     return $clfile;
// }

// http://localhost/synergy/php/makefilename.php
