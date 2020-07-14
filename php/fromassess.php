<?php
include_once '../include/mypaths.php';
spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$jk = $conn->run("SELECT * FROM ".TBL_ENTITIES." ORDER BY Granteename");

$grantarr = array('' => 'Not defined', 'sd1' => 'SDF Grantee', 'sd2' => 'NON-SDF Grantee');

$mk = $conn->run("SELECT * FROM ".TBL_DISTRICTS);

$distarr = array();

while ($r = $mk->fetch(PDO::FETCH_NUM)) {
    $distarr[$r[0]] = $r[1];
}

$jk1 = $conn->run("SELECT * FROM ".TBL_SKILLAREA." ORDER BY Skilldescription");
$v = 0;
while ($m = $jk1->fetch(PDO::FETCH_NUM)) {
    $response['skills'][$v]['Skillcode'] = $m[0];
    $response['skills'][$v]['Skilldescription'] = $m[1];
    $v += 1;
}

$sc = $conn->run("SELECT * FROM ".TBL_SECTORS);

$sectarr = array();

while ($k = $sc->fetch(PDO::FETCH_NUM)) {
    $sectarr[$k[0]] = $k[1];
}

for ($j = 0; $v = $jk->fetch(PDO::FETCH_OBJ); $j++) {
    $response['entities'][$j]['fileno'] = $v->fileno;
    $response['entities'][$j]['Granteename'] = $v->Granteename;
    $response['entities'][$j]['entype'] = $grantarr[$v->entype];
    $response['entities'][$j]['Districtcode'] = $distarr[$v->Districtcode];
    $response['entities'][$j]['Sectorcode'] = $sectarr[$v->Sectorcode];
    $response['entities'][$j]['pwindow'] = $v->pwindow;
}
$_SESSION['recno'] = 0;