<?php
// header('Content-Type: text/csv');
$filename = '../monthlyreports/'.$_POST['bring'];
// february2018200.xlsx
// february2019200.xlsx
$myfile = basename($filename);
$handle = fopen($filename,'r');
header("Content-Type: application/".$ext2);
header('Content-Disposition: attachment; filename="' . $myfile. '";');
fpassthru($handle);
fclose($handle);

/*
To get back the afftected file from the name rtrim the name fro the manufacture string
then remove it
to get the year rtrim the remain thing four for digits. this leaves us with the month of the return
*/

// $dirs = '../monthlyreports';
// $fileArray = array();
// if($handle = opendir($dirs)){
//     while($file = readdir($handle)){
//         if($file !='..' && $file !='.'){            
//             $fileArray[] = $file; 
//         }
//     }
//     closedir($handle);
//     print_r($fileArray);
// }
// echo "</ul>";
// http://localhost/synergy/php/download.php