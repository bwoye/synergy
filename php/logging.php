<?php
//session_start();
include_once "../include/sesscheck.php";
include_once "../include/connector.php";

$conn = Singleton::getInstance();

$response = array('error' => true);
if (!$conn) {
    $response['errmsg'] = "Unable to log into databases";
} else {
    $interval = $_SESSION['tzone'];
    //$conn->run("DELETE FROM translog WHERE fulname=?",['Samuel Bwoye']);
    $conn->run("DELETE FROM translog WHERE ISNULL(fulname)");
    if ($_SESSION['utype'] == 'Ad') {
        $mp = $conn->run("SELECT fulname,action, DATE_ADD(mydate,INTERVAL $interval MINUTE) AS logdate, DATE(DATE_ADD(mydate,INTERVAL $interval MINUTE)) as refdate FROM translog ORDER BY mydate DESC");

        for ($j = 0; $mk = $mp->fetch(); $j++) {
            $response['logs'][$j]['date'] = $mk->logdate;
            $response['logs'][$j]['action'] = $mk->action;
            $response['logs'][$j]['fulname'] = $mk->fulname;
            $response['logs'][$j]['refdate'] = $mk->refdate;
        }

        $dname = $conn->run("SELECT DISTINCT fulname FROM translog ORDER BY fulname");

        for ($j = 0; $fn = $dname->fetch(); $j++) {
            $response['names'][$j]['fulname'] = $fn->fulname;
        }

        $response['user'] = $_SESSION['userid'];
    } else {
        $mp = $conn->run("SELECT fulname,action, DATE_ADD(mydate,INTERVAL $interval MINUTE) AS logdate FROM translog WHERE fulname=? ORDER BY mydate DESC", [$_SESSION['fulname']]);

        for ($j = 0; $mk = $mp->fetch(); $j++) {
            $response['logs'][$j]['fulname'] = $mk->fulname;
            $response['logs'][$j]['date'] = $mk->logdate;
            $response['logs'][$j]['action'] = $mk->action;
            $response['logs'][$j]['refdate'] = $mk->refdate;
        }
        $response['names'][0]['fulname'] = $_SESSION['fulname'];
        $response['user'] = $_SESSION['userid'];
    }
}
echo json_encode($response);
