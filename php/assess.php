<?php
include_once "../include/mypaths.php";

$conn = Singleton::getInstance();

spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$response = array('error' => true);
if (!$conn) {
    $response['errmsg'] = "Unable to log into databases";
} else if (isset($_POST['enrol'])) {

    $skillarr = array();

    $jk = $conn->run("SELECT * FROM skillingareas ORDER BY Skilldescription");
    $v = 0;
    while ($m = $jk->fetch(PDO::FETCH_NUM)) {
        $skillarr[$m[0]] = $m[1];
        $response['skills'][$v]['Skillcode'] = $m[0];
        $response['skills'][$v]['Skilldescription'] = $m[1];
        $v += 1;
    }

    $mygender = array("M" => "Male", "F" => "Female");

    $pv = $conn->run("SELECT * FROM skilling WHERE fileno=? ORDER BY fulname", [$_POST['enrol']]);

    $response['counts'] = $pv->rowCount();
    for ($j = 0; $k = $pv->fetch(); $j++) {
        $response['mbrs'][$j]['fulname'] = $k->fulname;
        $response['mbrs'][$j]['Skillarea'] = $skillarr[$k->Skillcode];
        $response['mbrs'][$j]['selbline'] = $k->selbline;
        $response['mbrs'][$j]['recno'] = $k->recno;
        $response['mbrs'][$j]['selmline'] = $k->selmline;
        $response['mbrs'][$j]['seleline'] = $k->seleline;
        $response['mbrs'][$j]['gender'] = $k->Gender;
        $response['mbrs'][$j]['mact'] = $k->mact;
    }
    $cc = $conn->run("SELECT perms FROM userperm WHERE userid=? AND mypage=?", [$_SESSION['userid'], 'assess']);

    $j = $cc->fetch();
    $response['perms'] = $j->perms;
} else if (isset($_POST['myline'])) {
    //This for selecting the sebline,selmline or seleline
    $gval = $_POST['myline'];
    $fileno = $_POST['fileno'];
    //= str_replace('sel','',$_POST['myline']);
    $other = str_replace('sel', '', $_POST['myline']);

    $kk = $conn->run("SELECT * FROM skillvalues");
    $skarr = array();
    for ($j = 0; $vv = $kk->fetch(PDO::FETCH_NUM); $j++) {
        $skarr[$vv[0]] = $vv[1];
    }
    //print_r($skarr);

    $sql = "SELECT $gval,recno,$other FROM skilling WHERE fileno=?";
    //echo $sql;
    //exit();
    $vk = $conn->run($sql, [$fileno]);
    for ($j = 0; $mk = $vk->fetch(); $j++) {
        $response['guys'][$j]['recno'] = $mk->recno;
        $response['guys'][$j]['prop'] = $mk->$gval;
        $response['guys'][$j]['other'] = $skarr[$mk->$other];
        $response['guys'][$j]['myline'] = $mk->$gval;
    }

    $fn = str_replace('sel', '', $_POST['myline']);
    $response['doline'] = $fn;
    if (substr($fn, 0, 1) == 'b') {
        $response['kntool'] = 'bknowtool';
        $response['ustool'] = 'busetool';
        $response['aptool'] = 'bapptool';
    } else if (substr($fn, 0, 1) == 'm') {
        $response['kntool'] = 'mknowtool';
        $response['ustool'] = 'musetool';
        $response['aptool'] = 'mapptool';
    } else {
        $response['kntool'] = 'eknowtool';
        $response['ustool'] = 'eusetool';
        $response['aptool'] = 'eapptool';
    }
    $vf = $conn->run("SELECT gender,COUNT(*) FROM skilling WHERE fileno=? AND $gval=? GROUP BY gender", [$fileno, 1]);
    // echo "SELECT gender,COUNT(*) FROM skilling WHERE fileno=? AND $gval=? GROUP BY gender";
    // exit();
    $samparr = array('M' => '0', 'F' => '0');
    while ($rt = $vf->fetch(PDO::FETCH_NUM)) {
        $samparr[$rt[0]] = $rt[1];
    }
    $response['sm'] = $samparr['M'];
    $response['sf'] = $samparr['F'];
} else if (isset($_POST['oneline'])) {

    $recno = $_POST['recno'];
    $nval = $_POST['prop'];
    $ups = $_POST['oneline'];
    $other = str_replace('sel', '', $_POST['oneline']);
    $fileno = $_POST['fileno'];

    $sql = "UPDATE skilling SET $ups=$nval WHERE recno=?";

    $conn->run($sql, [$recno]);
    $response['prop'] = $nval;

    $kk = $conn->run("SELECT * FROM skillvalues");
    $skarr = array();
    for ($j = 0; $vv = $kk->fetch(PDO::FETCH_NUM); $j++) {
        $skarr[$vv[0]] = $vv[1];
    }

    $kql = "SELECT $other,fulname FROM skilling WHERE recno=?";

    // echo "SELECT $other FROM skilling WHERE recno=?";
    // exit();

    $pp = $conn->run($kql, [$recno]);
    $mm = $pp->fetch(PDO::FETCH_NUM);

    $response['other'] = $skarr[$mm[0]];
    $response['recno'] = $recno;
    $response['nval'] = $nval;

    $nums = $conn->run("SELECT gender,COUNT(*) FROM skilling WHERE fileno=? AND $ups=? GROUP BY gender", [$fileno, 1]);
    // echo "SELECT gender,COUNT(*) FROM skilling WHERE fileno=? AND $ups=?";
    // exit();
    $fmarr = array('M' => '0', 'F' => '0');
    while ($mv = $nums->fetch(PDO::FETCH_NUM)) {
        $fmarr[$mv[0]] = $mv[1];
    }

    //echo "SELECT gender,COUNT(*) FROM skilling WHERE fileno=$fileno AND $ups=1";
    $response['sf'] = $fmarr['F'];
    $response['sm'] = $fmarr['M'];

    $myname = $conn->run("SELECT Granteename FROM entities WHERE fileno=?", [$fileno]);
    $dname = $myname->fetch();

    $given = new DateTime();
    $takes = $given->format('Y-m-d H:i:s');

    if ($nval == 1) {

        $conn->run("INSERT INTO translog VALUES(?,?,?,?)", ['', $_SESSION['fulname'], 'Added ' . $mm[1] . ' to ' . $dname->Granteename . ' ' . $_POST['linename'] . ' sample ', $takes]);
    } else {
        $conn->run("INSERT INTO translog VALUES(?,?,?,?)", ['', $_SESSION['fulname'], 'Removed ' . $mm[1] . ' to ' . $dname->Granteename . ' ' . $_POST['linename'] . ' sample ', $takes]);
    }
} else if (isset($_POST['dorates'])) {

    //{"fileno":"w2/01/314/2017","dorates":"selmline","kntool":"mknowtool","ustool":"musetool","aptool":"mapptool","doline":"mline"}
    $fileno = $_POST['fileno'];
    $mysel = $_POST['dorates'];
    $kntool = $_POST['kntool'];
    $aptool = $_POST['aptool'];
    $ustool = $_POST['ustool'];
    $doline = $_POST['doline'];

    $conn->run("UPDATE skilling SET $doline=$kntool+$ustool+$aptool WHERE fileno=? AND $mysel=?", [$fileno, 1]);

    $pk = $conn->run("SELECT fulname,recno,$kntool,$ustool,$aptool,$doline FROM skilling WHERE fileno=? AND $mysel=? ORDER BY fulname", [$fileno, 1]);
    for ($p = 0; $mm = $pk->fetch(PDO::FETCH_NUM); $p++) {
        $response['guys'][$p]['fulname'] = $mm[0];
        $response['guys'][$p]['recno'] = $mm[1];
        $response['guys'][$p]['kntool'] = $mm[2];
        $response['guys'][$p]['ustool'] = $mm[3];
        $response['guys'][$p]['aptool'] = $mm[4];
        //$response['guys'][$p]['kntool'] = $mm[2];
    }
} else if (isset($_POST['changes'])) {
    $nval = $_POST['changes'];
    $recno = $_POST['recno'];
    $myfield = $_POST['upfile'];
    $ofields1 = $_POST['kntool'];
    $ofields2 = $_POST['ustool'];
    $ofields3 = $_POST['aptool'];
    $doline = $_POST['doline'];

    $conn->run("UPDATE skilling SET $myfield=? WHERE recno=?", [$nval, $recno]);
    $conn->run("UPDATE skilling SET $doline = $ofields1+$ofields2+$ofields3 WHERE recno=?", [$recno]);

    //Get skillvalues from skillvalues table

    $skarr = array();
    $vv = $conn->run("SELECT * FROM skillvalues");
    while ($p = $vv->fetch(PDO::FETCH_NUM)) {
        $skarr[$p[0]] = $p[1];
    }

    $mm = $conn->run("SELECT $doline,fileno,fulname FROM skilling WHERE recno=?", [$recno]);

    $msk = $mm->fetch(PDO::FETCH_NUM);
    $response['skill'] = $skarr[$msk[0]];
    $response['recno'] = $recno;

    $myname = $conn->run("SELECT Granteename FROM entities WHERE fileno=?", [$msk[1]]);
    $dname = $myname->fetch();

    if ($_SESSION['recno'] != $recno) {
        $linename = $_POST['linename'];
        $given = new DateTime();
        $takes = $given->format('Y-m-d H:i:s');
        $conn->run("INSERT INTO translog VALUES(?,?,?,?)", ['', $_SESSION['fulname'], 'Adjusted skill values for ' . $msk[2] . ' in ' . $dname->Granteename . ' sample', $takes]);

        $_SESSION['recno'] = $recno;
    }
} else {
    $response['entities'] = Entity::getFullTable();
    $response['skills'] = Skillingareas::getFullTable();
}
echo json_encode($response);
