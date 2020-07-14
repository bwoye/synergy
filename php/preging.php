<?php
//include_once "../include/sesscheck.php";
include_once "../include/mypaths.php";

spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$conn = Singleton::getInstance();

$delimiter = ",";
$filename = "mylogs_" . date('Ymd') . ".csv"; // Create file name
//drop table bwoye if it exists;
// $conn->run("DROP TABLE IF EXISTS bwoye");

// //Create the temporary table bwoye and add records
// $conn->run("CREATE TEMPORARY TABLE bwoye LIKE myfun");

// //Put their records excluding logged in and Logged out;

// $conn->run("INSERT INTO bwoye (fulname) SELECT fulname FROM skilling");
$pp = $conn->run("SELECT * FROM entities");
$f = fopen('php://memory', 'w');
$delimiter =',';
$fields = array('Entity','Contact Person','Telephone');
fputcsv($f, $fields, $delimiter);

//output each row of the data, format line as csv and write to file pointer
while ($row = $pp->fetch(PDO::FETCH_ASSOC)) {
    //if (preg_match('/^7*/', $row['Phonecontact'])) {
        $lineData = array($row['Granteename'],$row['contperson'],$row['contphone']);
        fputcsv($f, $lineData, $delimiter);
    //}
}

//move back to beginning of file
fseek($f, 0);

//set headers to download file rather than displayed
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '";');

//output all remaining data on a file pointer
fpassthru($f);
//   http://localhost/synergy/php/preging.php