<?php
include '../include/mypaths.php';
spl_autoload_register(function ($class_name) {
    include MODELS.DS.$class_name . ".php";
});

$conn = Singleton::getInstance();

$response = array('error' => true);
if (!$conn) {
    $response['errmsg'] = "Unable to log into databases";
} else if (isset($_POST['enrol'])) {

    $skillarr = array();

    $jk = $conn->run("SELECT * FROM ". TBL_SKILLAREA. " ORDER BY Skilldescription");
    $v = 0;

    for($j=0;$p=$jk->fetch(PDO::FETCH_ASSOC);$j++){
        $response['skills'][] = $p;
    }
   

    $pv = $conn->run("SELECT a.*,b.Skilldescription AS Skillarea FROM " .TBL_SKILLING." AS a," .TBL_SKILLAREA." As b WHERE fileno=? AND a.Skillcode = b.skillcode ORDER BY fulname", [$_POST['enrol']]);

    $response['counts'] = $pv->rowCount();
    for ($j = 0; $k = $pv->fetch(); $j++) {        
        $response['mbrs'][] = $k;
    }

    $finds = $conn->run("SELECT mact,gender,COUNT(*) FROM ".TBL_SKILLING." WHERE fileno=? GROUP BY mact,gender", [$_POST['enrol']]);
    $response['co']['F'] = 0;
    $response['co']['M'] = 0;
    $response['tr']['F'] = 0;
    $response['tr']['M'] = 0;
    $response['dr']['F'] = 0;
    $response['dr']['M'] = 0;

    for ($f = 0; $p = $finds->fetch(PDO::FETCH_NUM); $f++) {
        $response[$p[0]][$p[1]] = $p[2];
    }

    $cc = $conn->run("SELECT perms FROM ".TBL_USERPERM." WHERE userid=? AND mypage=?", [$_SESSION['userid'], 'enrol']);

    $j = $cc->fetch();
    $response['perms'] = $j->perms;
} elseif (isset($_POST['emppass'])) {

    $emppass = crypt($_POST['emppass'], $conn->getSalt());
    $conn->run("UPDATE ".TBL_ENTITIES." SET emppass=:emppass WHERE fileno=:fileno", ['emppass' => $emppass, 'fileno' => $_POST['fileno']]);

    $response['error'] = false;
    $response['errmsg'] = "Password Assignment Successful";

} else if (isset($_POST['adding'])) {

    $fileno = $_POST['adding'];
    $fulname = $_POST['fulname'];
    $Gender = $_POST['Gender'];
    $Phonecontact = $_POST['Phonecontact'];
    $Skillcode = $_POST['Skillcode'];
    $skilling = $_POST['skilling'];
    $mact = $_POST['mact'];

    try {
        $conn->beginTransaction();
        $conn->run("INSERT INTO ".TBL_SKILLING." (recno,fileno,fulname,Phonecontact,Skillcode,Gender,mact) VALUES(?,?,?,?,?,?,?)", [NULL, $fileno, $fulname, $Phonecontact, $Skillcode, $Gender, $mact]);

        $response['recno'] = $conn->userInsert();


        // $pk = $conn->run("SELECT Granteename FROM ".TBL_ENTITIES." WHERE fileno=?", [$fileno]);
        // $jk = $pk->fetch();
        $pk = Entity::getMember($fileno);

        $given = new DateTime();
        $takes = $given->format('Y-m-d H:i:s');
        $conn->run("INSERT INTO translog VALUES(?,?,?,?)", [NULL, $_SESSION['fulname'], 'Added ' . $fulname . ' to ' . $pk->Granteename, $takes]);

        $conn->commit();

        $rt = $conn->run("SELECT COUNT(*) FROM ".TBL_SKILLAREA." WHERE fileno=?", [$fileno]);

        $vx = $rt->fetch(PDO::FETCH_NUM);
        $response['mact'] = $mact;
        $response['fulname'] = $fulname;
        $response['Phonecontact'] = $Phonecontact;
        $response['Skillcode'] = $Skillcode;
        $response['skilling'] = $skilling;
        $response['counts'] = $vx[0];
        $response['Gender'] = $Gender;

        $finds = $conn->run("SELECT mact,gender,COUNT(*) FROM ".TBL_SKILLING." WHERE fileno=? GROUP BY mact,gender", [$fileno]);
        $response['co']['F'] = 0;
        $response['co']['M'] = 0;
        $response['tr']['F'] = 0;
        $response['tr']['M'] = 0;
        $response['dr']['F'] = 0;
        $response['dr']['M'] = 0;

        for ($f = 0; $p = $finds->fetch(PDO::FETCH_NUM); $f++) {
            $response[$p[0]][$p[1]] = $p[2];
        }       

    } catch (PDOException $e) {
        $conn->rollBack();
        $response['error'] = true;
        $response['errmsg'] = "Member was not added to enrollment";
    }
} else if (isset($_POST['editing'])) {

    $recno = $_POST['editing'];
    $Phonecontact = $_POST['Phonecontact'];
    $Gender = $_POST['Gender'];
    $mact = $_POST['mact'];
    $Skillcode = $_POST['Skillcode'];
    $fulname = $_POST['fulname'];
    $skilling = $_POST['skilling'];

    //confirm if user did something
    $cf = $conn->run("SELECT * FROM ".TBL_SKILLING." WHERE recno=?", [$recno]);
    $cf2 = $cf->fetch();

    if ($cf2->fulname == $fulname && $cf2->Phonecontact == $Phonecontact && $cf2->Gender == $Gender && $cf2->Skillcode == $Skillcode && $cf2->mact == $mact) {
        return false;
        exit();
    }

    $conn->run("UPDATE ".TBL_SKILLING." SET fulname=:fulname,Skillcode=:Skillcode,Gender=:Gender,mact=:mact,Phonecontact=:Phonecontact WHERE recno=:recno", ['fulname' => $fulname, 'Skillcode' => $Skillcode, 'Phonecontact' => $Phonecontact, 'Gender' => $Gender, 'mact' => $mact, 'recno' => $recno]);

    //This is what we are returning
    $response['recno'] = $recno; // = $_POST['editing'];
    $response['Phonecontact'] = $Phonecontact; //=$_POST['Phonecontact'];
    $response['Gender'] = $Gender; //=$_POST['Gender'];
    $response['mact'] = $mact; // = $_POST['mact'];
    $response['Skillcode'] = $Skillcode; // = $_POST['Skillcode'];
    $response['fulname'] = $fulname; // = $_POST['fulname'];
    $response['skilling'] = $skilling;

    $ff = $conn->run("SELECT fileno FROM ".TBL_SKILLING." WHERE recno=?", [$recno]);
    $mg = $ff->fetch();

    $finds = $conn->run("SELECT mact,gender,COUNT(*) FROM ".TBL_SKILLING." WHERE fileno=? GROUP BY mact,gender", [$mg->fileno]);

    $response['co']['F'] = 0;
    $response['co']['M'] = 0;
    $response['tr']['F'] = 0;
    $response['tr']['M'] = 0;
    $response['dr']['F'] = 0;
    $response['dr']['M'] = 0;

    for ($f = 0; $p = $finds->fetch(PDO::FETCH_NUM); $f++) {
        $response[$p[0]][$p[1]] = $p[2];
    }

    $given = new DateTime();
    $takes = $given->format('Y-m-d H:i:s');
    $conn->run("INSERT INTO ".TBL_TRANS." VALUES(?,?,?,?)", [NULL, $_SESSION['fulname'], 'Edited enrollment', $takes]);

} elseif (isset($_POST['pdelete'])) {

    $ff = $conn->run("SELECT fulname,fileno FROM ".TBL_SKILLING." WHERE recno=?", [$_POST['pdelete']]);
    $mg = $ff->fetch();

    $kk = $conn->run("SELECT Granteename FROM ".TBL_ENTITIES." WHERE fileno=?", [$mg->fileno]);
    $qc = $kk->fetch();

    $given = new DateTime();
    $takes = $given->format('Y-m-d H:i:s');
    $conn->run("INSERT INTO translog VALUES(?,?,?,?)", [NULL, $_SESSION['fulname'], 'deleted ' . $mg->fulname . ' from ' . $qc->Granteename . ' enrollment', $takes]);

    $conn->run("DELETE FROM ".TBL_SKILLING." WHERE recno=?", [$_POST['pdelete']]);

    $finds = $conn->run("SELECT mact,gender,COUNT(*) FROM ".TBL_SKILLING." WHERE fileno=? GROUP BY mact,gender", [$mg->fileno]);

    $response['co']['F'] = 0;
    $response['co']['M'] = 0;
    $response['tr']['F'] = 0;
    $response['tr']['M'] = 0;
    $response['dr']['F'] = 0;
    $response['dr']['M'] = 0;

    for ($f = 0; $p = $finds->fetch(PDO::FETCH_NUM); $f++) {
        $response[$p[0]][$p[1]] = $p[2];
    }
} else {
    // $jk = $conn->run("SELECT * FROM entities ORDER BY Granteename");

    // for ($j = 0; $v = $jk->fetch(PDO::FETCH_OBJ); $j++) {
    //     $response['ents'][$j]['fileno'] = $v->fileno;
    //     $response['ents'][$j]['Granteename'] = $v->Granteename;
    //     $response['ents'][$j]['entype'] = $grantarr[$v->entype];
    //     $response['ents'][$j]['Districtcode'] = $distarr[$v->Districtcode];
    //     $response['ents'][$j]['Sectorcode'] = $sectarr[$v->Sectorcode];
    //     $response['ents'][$j]['pwindow'] = $v->pwindow;
    // }

    // $response['ents']= Entity::getFullTable(['fileno','Granteename','entype','Districtcode','Sectorcode','pwindow']);

    $grantarr = array('' => 'Not defined', 'sd1' => 'SDF Grantee', 'sd2' => 'NON-SDF Grantee');

    // $mk = $conn->run("SELECT * FROM ".TBL_DISTRICTS);

    // $distarr = array();

    // while ($r = $mk->fetch(PDO::FETCH_NUM)) {
    //     $distarr[$r[0]] = $r[1];
    // }

    $qry = $conn->run("SELECT a.fileno,a.Granteename,a.entype,a.pwindow,b.Districtname,c.Sectordescription FROM ".TBL_ENTITIES." a LEFT JOIN ".TBL_DISTRICTS." b USING (Districtcode) LEFT JOIN ".TBL_SECTORS." c USING(Sectorcode) ORDER BY a.Granteename");

    for($j=0;$p=$qry->fetch(PDO::FETCH_ASSOC);$j++){
        $response['ents'][]= $p;
    }
   

    

    $jk1 = $conn->run("SELECT * FROM ".TBL_SKILLAREA." ORDER BY Skilldescription");
    $v = 0;
    while ($m = $jk1->fetch(PDO::FETCH_NUM)) {
        $response['skills'][$v]['Skillcode'] = $m[0];
        $response['skills'][$v]['Skilldescription'] = $m[1];
        $v += 1;
    }

    // $sc = $conn->run("SELECT * FROM sectors");

    // $sectarr = array();

    // while ($k = $sc->fetch(PDO::FETCH_NUM)) {
    //     $sectarr[$k[0]] = $k[1];
    // }   
}
echo json_encode($response);
