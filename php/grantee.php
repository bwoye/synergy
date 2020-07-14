<?php

include_once "../include/mypaths.php";

if (!isset($_SESSION['myfile'])) {
    header("location: ../index.php");
    exit();
}

spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});
$conn = Singleton::getInstance();
$response = array("error" => true);
$fileno = $_SESSION['myfile'];
if (isset($_POST['adding'])) {
    $gArr = $_POST;
    unset($gArr['adding']);
    $gArr['fileno'] = $fileno;
    $gArr['recno'] = NULL;
    //print_r($gArr);

    //Count the entered records
    $mf = $conn->run("SELECT COUNT(*) FROM " . TBL_SKILLING . " WHERE fileno=:fileno", ["fileno" => $fileno]);
    $many = $mf->fetch(PDO::FETCH_NUM)[0];

    $allowed = $conn->run("SELECT numroll FROM " . TBL_ENTITIES . " WHERE fileno=:fileno", ["fileno" => $fileno]);
    $numall = $allowed->fetch(PDO::FETCH_NUM)[0];

    if ($many >= $numall) {
        exit();
    }
    $conn->beginTransaction();

    try {
        $conn->run("INSERT INTO " . TBL_SKILLING . " (recno,fulname,Gender,email,Phonecontact,Skillcode,fileno) VALUES(:recno,:fulname,:Gender,:email,:Phonecontact,:Skillcode,:fileno)", $gArr);
        $response['recno'] = $conn->userInsert();
        $response['error'] = false;
        $conn->commit();
    } catch (PDOException $e) {
        $conn->rollBack();
        //echo $e->getMessage();
        $response['error'] = true;
    }
} elseif (isset($_POST['recno'])) {
    $vArr = $_POST;

    $conn->run("UPDATE " . TBL_SKILLING . " SET fulname=:fulname,Gender=:Gender,email=:email,Phonecontact=:Phonecontact,Skillcode=:Skillcode WHERE recno=:recno", $vArr);
} else if (isset($_POST['delpart'])) {
    $conn->run("DELETE FROM " .TBL_SKILLING. " WHERE recno=:idno", ["idno" => $_POST['delpart']]);
} elseif (isset($_POST['myreturns'])) {
    $mk = $conn->run("SELECT * FROM " . TBL_RETURNS . " WHERE fileno=:fileno", ["fileno" => $fileno]);
    for ($j = 0; $gg = $mk->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['returns'][] = $gg;
    }
}
if (isset($_POST['fileins'])) {
    date_default_timezone_set('UTC');
    if ($_FILES['file']['name'] != '') {
        $filename = $_FILES['file']['name'];
        $tmpfile = $_FILES['file']['tmp_name'];
        $ferror = $_FILES['file']['error'];
        $fsize = $_FILES['file']['size'];

        $allowed = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv');
        $fext = explode('.', $filename);
        $actualExt = end($fext);
        $fnew = uniqid('', true);
        $actname = '../monthlyreports/' . $fnew . "." . $actualExt;
        $ng = $_POST;
        unset($ng['fileins']);
        $ng['fileno'] = $fileno;
        $ng['filingdate'] = date('Ymd');
        $ng['retperiod'] = date('M', strtotime($_POST['myperiod'])) . " " . date('Y', strtotime($_POST['myperiod']));
        $ng['idno'] = null;
        $ng['filename'] = $fnew . "." . $actualExt;
        unset($ng['myperiod']);

        //print_r($ng);
        if (in_array($actualExt, $allowed)) {
            if (move_uploaded_file($tmpfile, $actname)) {
                $conn = Singleton::getInstance();

                $conn->beginTransaction();
                try {
                    $conn->run("INSERT INTO " . TBL_RETURNS . " VALUES(:idno,:fileno,:filingdate,:retperiod,:filename)", $ng);
                    $response['myinsert'] = $conn->userInsert();
                    $response['error'] = false;
                    $conn->commit();
                } catch (PDOException $e) {
                    $conn->rollBack();
                    $response['errmsg'] = $e->getMessage();
                    $response['error'] = true;
                }
            } else {
                unlink($actname);
            }
        }
    }

    $pk = $conn->run("SELECT * FROM " . TBL_RETURNS . " WHERE idno=:idno", ["idno" => $response['myinsert']]);
    $kk = $pk->fetch(PDO::FETCH_ASSOC);
    $response['myreturn'] = $kk;
} elseif (isset($_POST['delreturn'])) {
    $ng = $_POST;
    unset($ng['delreturn']);

    unlink('../monthlyreports/' . $ng['filename']);
    unset($ng['filename']);
    $conn->run("DELETE FROM " . TBL_RETURNS . " WHERE idno=:idno", $ng);
} else {
    //select a.recno,a.fulname,a.nationalidno,b.Skilldescription,a.Gender,a.Phonecontact from skilling a left join skillingareas b on a.Skillcode = b.skillcode where a.fileno='W2/639/2017';
    $ll = $conn->run("SELECT a.recno,a.Gender,a.email,a.fulname,a.Skillcode,a.nationalidno,b.Skilldescription,a.Phonecontact FROM " . TBL_SKILLING . " a LEFT JOIN " . TBL_SKILLAREA . " b ON a.Skillcode=b.skillcode WHERE a.fileno=:fileno ORDER BY a.fulname", ["fileno" => $fileno]);

    for ($j = 0; $m = $ll->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['participants'][] = $m;
    }

    $vv = array();
    $response['gdistr']['F'] = 0;
    $response['gdistr']['M'] = 0;
    if (array_key_exists('participants', $response)) {

        foreach ($response['participants'] as $key => $val) {
            foreach ($val as $m => $n) {
                if ($m == 'Gender') {
                    $vv[] = $n;
                    break;
                }
            }
        }
        $response['gdistr'] = $vv;
    }



    //Now the number of males and females;
    $response['mygender'] = array_count_values($vv);

    //Filing history
    $mm = $conn->run("SELECT * FROM " . TBL_RETURNS . " WHERE fileno=:fileno", ["fileno" => $fileno]);

    for ($j = 0; $k = $mm->fetch(PDO::FETCH_ASSOC); $j++) {
        //$response['returns'][] = $k;
    }

    //Now the name,budget and payments
    $fname = $conn->run("SELECT a.*,sum(b.amount) AS 'amount',sum(b.ownamount) AS 'ownamount',c.Districtname AS 'distname',d.Sectordescription FROM " . TBL_ENTITIES . " a LEFT JOIN " . TBL_PAYMENTS . " b USING(fileno) LEFT JOIN " . TBL_DISTRICTS . " c USING(Districtcode) LEFT JOIN " . TBL_SECTORS . " d USING(Sectorcode) WHERE a.fileno=:fileno", ["fileno" => $fileno]);

    for ($j = 0; $jk = $fname->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['grdetails'] = $jk;
    }

    $mf = $conn->run("SELECT * FROM " . TBL_SKILLAREA);
    for ($h = 0; $k = $mf->fetch(PDO::FETCH_ASSOC); $h++) {
        $response['skarea'][] = $k;
    }

    $gh = $conn->run("SELECT mact,COUNT(IF(Gender='F',1,NULL)) AS 'Female',COUNT(IF(Gender='M',1,NULL)) AS 'Male' FROM " . TBL_SKILLING . " WHERE fileno=:fileno GROUP BY mact", ["fileno" => $fileno]);


    if ($gh->rowCount() < 1) {
        $response['skillstatus']['dr']['Female'] = 0;
        $response['skillstatus']['dr']['Male'] = 0;
        $response['skillstatus']['co']['Female'] = 0;
        $response['skillstatus']['co']['Male'] = 0;
        $response['skillstatus']['tr']['Female'] = 0;
        $response['skillstatus']['tr']['Male'] = 0;
    } else {
        for ($j = 0; $k = $gh->fetch(PDO::FETCH_NUM); $j++) {
            $response['skillstatus'][$k[0]]['Female'] = $k[1];
            $response['skillstatus'][$k[0]]['Male'] = $k[2];
        }
    }
}

echo json_encode($response);
