<?php

//include "../../include/connector.php";
class Entity extends DataObject
{
    protected $data = array(
        "fileno" => '',
        "Beneficiaryno" => 0,
        "entype" => '',
        "Granteename" => '',
        "paddress" => '',
        "contperson" => '',
        "contphone" => '',
        "Districtcode" => 0,
        "Sectorcode" => 0,
        "pwindow" => 0,
        "duration" => 0,
        "startdate" => '0000-00-00',
        "enddate" => '0000-00-00',
        "Appbudget" => 0,
        "grcont" => 0,
        "dform" => 'D',
        "numroll" => 0,
        "emppass" => '',
        "catid" => 0,
        "catcreteria" => 0,
    );

    private $response = [];

    public static function getMember($fileno)
    {
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM " . TBL_ENTITIES . " WHERE fileno = ?", [$fileno]);
        $row = $fr->fetch(PDO::FETCH_ASSOC);

        return new Entity($row);
    }

    /**
     * set password for grantee
     */
    public function setNewPassword($newone)
    {
        if (password_verify($_SESSION['kpass'], $this->data['emppass'])) {
            $hashedPass = password_hash($newone, PASSWORD_DEFAULT);
            $conn = parent::connect();
            $sql = $conn->run("UPDATE " . TBL_ENTITIES . " SET emppass=:newone WHERE fileno=:fileno", ["newone" => $hashedPass, "fileno" => $this->data['fileno']]);
            if($sql){
                $_SESSION['kpass'] = $newone;
                return true;
            }  else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function saveRecord()
    {
        $mydate = $this->data['startdate'];


        $myinterval = array('D' => 'Day', 'M' => 'Month');

        $this->data['enddate'] = date('Y-m-d', strtotime($mydate . ' ' . $this->data['duration'] . ' ' . $myinterval[$this->data['dform']]));

        // print_r($this->data);
        // exit();
        $dv = $this->conn->run("INSERT INTO " . TBL_ENTITIES . " (fileno,Beneficiaryno,entype,Granteename,paddress,contperson,contphone,Districtcode,Sectorcode,pwindow,duration,startdate,enddate,Appbudget,grcont,dform,numroll,emppass,catid,catcreteria) VALUES(:fileno,:Beneficiaryno,:entype,:Granteename,:paddress,:contperson,:contphone,:Districtcode,:Sectorcode,:pwindow,:duration,:startdate,:enddate,:Appbudget,:grcont,:dform,:numroll,:emppass,:catid,:catcreteria)", $this->data);

        if (!$dv) {
            $this->response['error'] = true;
            $this->response['errmsg'] = "Their was an error saving record.";

            echo json_encode($this->response);
        } else {
            $this->response['error'] = false;
            $this->response['errmsg'] = "Record saved";

            $mm = $this->conn->run("SELECT a.*,b.Sectordescription,c.Districtname,d.tname AS Entitytypename,e.description,f.description AS catdes FROM " . TBL_ENTITIES . " AS a," . TBL_SECTORS . " AS b," . TBL_DISTRICTS . " AS c," . TBL_ETYPES . " AS d," . TBL_CRITERIA . " AS e," . TBL_CRITERIAREASON . " AS f WHERE a.Districtcode=c.Districtcode AND a.Sectorcode = b.Sectorcode AND a.entype = d.mytype AND a.catid=e.catid AND a.catcreteria=f.catcreteria AND fileno =:fileno", ['fileno' => $this->data['fileno']]);

            for ($i = 0; $gg = $mm->fetch(PDO::FETCH_ASSOC); $i++) {
                $this->response['Entity'] = $gg;
            }

            $pv = $this->conn->run("SELECT COUNT(*) AS many FROM " . TBL_ENTITIES);
            for ($i = 0; $m = $pv->fetch(PDO::FETCH_ASSOC); $i++) {
                //$response['Entity']['counts'] = $m['many']; 
                $this->response['counts'] = $m['many'];
            }

            $kv = $this->conn->run("SELECT sum(Appbudget),sum(grcont),sum(Beneficiaryno) FROM entities");
            $mh = $kv->fetch(PDO::FETCH_NUM);
            $this->response['Entity']['iappnum'] = $mh[2];
            $this->response['Entity']['iappbudget'] = $mh[0];
            $this->response['Entity']['iownbudget'] = $mh[1];
            //$response = array_merge($response,$parr);
        }

        echo json_encode($this->response);
    }

    public function delete()
    {
        $conn = parent::connect();
        $this->response = array('error' => true);
        $dv = $this->conn->run("SELECT Granteename FROM " . TBL_ENTITIES . " WHERE fileno=:myfile", [$this->data['fileno']]);
        $ll = $dv->fetch();
        $Granteename = $ll->Granteename;
        try {
            $conn->beginTransaction();

            $Granteename = $_POST['namex'];

            $this->conn->run("DELETE FROM " . TBL_ENTITIES . " WHERE fileno=?", [$this->data['fileno']]);
            $vk = $this->conn->run("SELECT count(*) FROM " . TBL_ENTITIES);
            $kl = $vk->fetch(PDO::FETCH_NUM);
            $response['counts'] = $kl[0];
            $respoonse['errmsg'] = "Record was deleted";
            $response['error'] = false;

            $kv = $this->conn->run("SELECT sum(Appbudget),sum(grcont),sum(Beneficiaryno) FROM " . TBL_ENTITIES);

            $mh = $kv->fetch(PDO::FETCH_NUM);
            $this->response['uappnum'] = $mh[2];
            $this->response['uappbudget'] = $mh[0];
            $this->response['uownbudget'] = $mh[1];

            $given = new DateTime();
            $takes = $given->format('Y-m-d H:i:s');

            // $conn->run("INSERT INTO ".TBL_TRANS." VALUES(?,?,?,?)", [NULL, $_SESSION['fulname'], "Deleted Grantee $Granteename", $takes]);
            $logs = new Translog(NULL, $_SESSION['fulname'], "Deleted Grantee $Granteename", $takes);
            $logs->addItem();
            $conn->commit();
        } catch (PDOException $e) {
            $conn->rollBack();
            $this->response['errmsg'] = "Entity was not Deleted. It either has a payment or Enrollment attached to it";
            //$response['error'] = true;
        }

        echo json_encode($this->response);
    }

    public function SaveEdits($oldfile)
    {

        //print_r($this);
        $conn = parent::connect();

        $nOne = (array_merge($this->data, array('oldfile' => $oldfile)));
        unset($nOne['Beneficiaryno'], $nOne['emppass']);

        $searchfile = $this->data['fileno'];
        $response = array();

        $mydate = $nOne['startdate'];
        $myinterval = array('D' => 'day', 'M' => 'Month');



        $enddate = date('Y-m-d', strtotime($mydate . ' ' . $nOne['duration'] . ' ' . $myinterval[$nOne['dform']]));
        $nOne['enddate'] = $enddate;

        // echo $nOne['enddate'];
        // exit();

        //$conn = Singleton::getInstance();
        $conn = parent::connect();

        $conn->run("UPDATE " . TBL_ENTITIES . " SET fileno=:fileno,entype=:entype,Granteename=:Granteename,paddress=:paddress,contperson=:contperson,contphone=:contphone,Districtcode=:Districtcode,Sectorcode=:Sectorcode,pwindow=:pwindow,duration=:duration,startdate=:startdate,enddate=:enddate,Appbudget=:Appbudget,grcont=:grcont,dform=:dform,numroll=:numroll,catid=:catid,catcreteria=:catcreteria WHERE fileno=:oldfile", $nOne);


        $sql = "SELECT  a.*,DATE(DATE_ADD(a.startdate,INTERVAL " .  $_SESSION['tzone'] . " MINUTE)) AS startdate,DATE(DATE_ADD(a.enddate,INTERVAL " .  $_SESSION['tzone'] . " MINUTE)) AS enddate,b.tname AS Entitytypename,c.Sectordescription,d.description,e.description AS catdesc,f.Districtname FROM " . TBL_ENTITIES . " a LEFT JOIN " . TBL_ETYPES . " b ON a.entype=b.mytype LEFT JOIN " . TBL_SECTORS . " c USING(Sectorcode) LEFT JOIN " . TBL_CRITERIA . " d USING(catid) LEFT JOIN " . TBL_CRITERIAREASON . " e USING(catcreteria) LEFT JOIN " . TBL_DISTRICTS . " f USING(Districtcode) WHERE a.fileno=:fileno";

        // $sql = "SELECT a.*,c.tname as Entitytypename,d.Sectordescription,e.description,f.description AS catdes,DATE(DATE_ADD(a.startdate,interval " . $_SESSION['tzone'] . " MINUTE)) AS startdate,DATE(DATE_ADD(a.enddate,interval " . $_SESSION['tzone'] . " MINUTE)) AS enddate,b.Districtname FROM " . TBL_ENTITIES . " As a," . TBL_DISTRICTS . " AS b," . TBL_ETYPES . " as c," . TBL_SECTORS . " AS d," . TBL_CRITERIA . " AS e," . TBL_CRITERIAREASON . " AS f WHERE a.Districtcode = b.Districtcode AND a.entype=c.mytype AND a.Sectorcode = d.Sectorcode AND a.catid = e.catid AND a.catcreteria = f.catcreteria AND a.fileno=:fileno";

        // $sql = "SELECT a.*,c.tname as Entitytypename,d.Sectordescription,e.description,f.description AS catdes,DATE(DATE_ADD(a.startdate,interval 180 MINUTE)) AS startdate,DATE(DATE_ADD(a.enddate,interval 180 MINUTE)) AS enddate,b.Districtname FROM " . TBL_ENTITIES . " As a," . TBL_DISTRICTS . " AS b," . TBL_ETYPES . " as c," . TBL_SECTORS . " AS d,".TBL_CRITERIA." AS e,".TBL_CRITERIAREASON." AS f WHERE a.Districtcode = b.Districtcode AND a.entype=c.mytype AND a.Sectorcode = d.Sectorcode AND a.catid = e.catid AND a.catcreteria = f.catcreteria AND a.fileno=:fileno";

        $dv = $conn->run($sql, ['fileno' => $searchfile]);
        for ($i = 0; $j = $dv->fetch(PDO::FETCH_ASSOC); $i++) {
            $response = $j;
        }



        $kv = $conn->run("SELECT sum(Appbudget),sum(grcont),sum(Beneficiaryno) FROM " . TBL_ENTITIES);

        $mh = $kv->fetch(PDO::FETCH_NUM);
        $response['uappnum'] = $mh[2];
        $response['uappbudget'] = $mh[0];
        $response['uownbudget'] = $mh[1];
        $response['errmsg'] = "Record updated";

        //print_r($this);
        $response['filenonew'] = $searchfile;
        echo json_encode($response);
    }

    public static function showAll()
    {
        $conn = Singleton::getInstance();


        //$sql="SELECT  a.*,DATE(DATE_ADD(a.startdate,INTERVAL 180 MINUTE)) AS startdate,DATE(DATE_ADD(a.enddate,INTERVAL 180 MINUTE)) AS enddate,b.tname AS Entitytypename,c.Sectordescription,d.description,e.description AS catdesc,f.Districtname FROM entities as a,etypes AS B,sectors AS c,idcat AS d,idcriteria AS e,districts as f  WHERE a.entype=b.mytype  AND c.Sectorcode = a.Sectorcode AND a.catid=d.catid AND a.catcreteria = e.catcreteria AND a.Districtcode=f.Districtcode ORDER BY a.Granteename";      

        $sql = "SELECT  a.*,DATE(DATE_ADD(a.startdate,INTERVAL " .  $_SESSION['tzone'] . " MINUTE)) AS startdate,DATE(DATE_ADD(a.enddate,INTERVAL " .  $_SESSION['tzone'] . " MINUTE)) AS enddate,b.tname AS Entitytypename,c.Sectordescription,d.description,e.description AS catdesc,f.Districtname FROM " . TBL_ENTITIES . " a LEFT JOIN " . TBL_ETYPES . " b ON a.entype=b.mytype LEFT JOIN " . TBL_SECTORS . " c USING(Sectorcode) LEFT JOIN " . TBL_CRITERIA . " d USING(catid) LEFT JOIN " . TBL_CRITERIAREASON . " e USING(catcreteria) LEFT JOIN " . TBL_DISTRICTS . " f USING(Districtcode) ORDER BY a.Granteename";

        $ff = $conn->run($sql);
        $response = array();
        for ($i = 0; $gg = $ff->fetch(PDO::FETCH_ASSOC); $i++) {
            $response['entities'][$i]= $gg;
        }

        $response['sector'] = Sectors::getAll();

        $ent = array('' => 'unknown', 'sd1' => 'SDF Grantee', 'sd2' => 'NON-SDF Grantee');
        $j = 0;
        foreach ($ent as $key => $val) {
            $response['dtypes'][$j]['code'] = $key;
            $response['dtypes'][$j]['name'] = $val;
            $j += 1;
        }

        $kv = $conn->run("SELECT sum(Appbudget),sum(grcont),sum(Beneficiaryno) FROM " . TBL_ENTITIES);

        $mh = $kv->fetch(PDO::FETCH_NUM);
        $response['appnum'] = $mh[2];
        $response['appbudget'] = $mh[0];
        $response['ownbudget'] = $mh[1];

        $fm = array('F' => "0", 'M' => "0");

        $dd = $conn->run("SELECT gender,COUNT(*) FROM " . TBL_SKILLING . " GROUP BY gender");
        //$kk = $dd->fetch(PDO::FETCH_NUM);

        while ($f = $dd->fetch(PDO::FETCH_NUM)) {
            $fm[$f[0]] = $f[1];
        }
        $response['sm'] = $fm['M'];
        $response['sf'] = $fm['F'];

        $finds = $conn->run("SELECT mact,gender,COUNT(*) FROM " . TBL_SKILLING . " GROUP BY mact,gender");

        for ($f = 0; $p = $finds->fetch(PDO::FETCH_NUM); $f++) {
            $response[$p[0]][$p[1]] = $p[2];
        }


        $pv = $conn->run("SELECT COUNT(*) AS many FROM " . TBL_ENTITIES);
        for ($i = 0; $m = $pv->fetch(PDO::FETCH_ASSOC); $i++) {
            //$response['Entity']['counts'] = $m['many']; 
            $response['counts'] = $m['many'];
        }


        //Use the districts class here

        $response['districts'] = Districts::getFullTable();

        // $pl = $conn->run("SELECT * FROM districts ORDER BY Districtname");

        // for ($i = 0; $kk = $pl->fetch(PDO::FETCH_ASSOC); $i++) {
        //     $response['districts'][] = $kk;
        // }


        //Use Idcat class

        $response['idcat'] = Idcat::getFullTable();
        // $jim = $conn->run("SELECT * FROM idcat");
        // for ($i = 0; $jk = $jim->fetch(PDO::FETCH_ASSOC); $i++) {
        //     $response['idcat'][] = $jk;
        // }
        
        $response['idcriteria'] = Idcriteria::getFullTable();
        // $jimk = $conn->run("SELECT * FROM " . TBL_CRITERIAREASON);
        // for ($i = 0; $jk = $jimk->fetch(PDO::FETCH_ASSOC); $i++) {
        //     $response['idcriteria'][$i] = $jk;
        // }
        //$cc = $conn->run("SELECT perms FROM userperm WHERE userid=? AND mypage=?", ['gloria', 'entities']);
        $cc = $conn->run("SELECT perms FROM " . TBL_USERPERM . " WHERE userid=? AND mypage=?", [$_SESSION['userid'], 'entities']);

        $j = $cc->fetch();
        $response['perms'] = $j->perms;
        // header("Content-Type:Application/json");
        // echo json_encode($response);     
        return $response;
    }

    public static function getFullTable($args = [])
    {
        $conn = Singleton::getInstance();
        //$response=[];
        if (!$args) {
            $sql = "SELECT  a.*,DATE(DATE_ADD(a.startdate,INTERVAL " .  $_SESSION['tzone'] . " MINUTE)) AS startdate,DATE(DATE_ADD(a.enddate,INTERVAL " .  $_SESSION['tzone'] . " MINUTE)) AS enddate,b.tname AS Entitytypename,c.Sectordescription,d.description,e.description AS catdesc,f.Districtname FROM " . TBL_ENTITIES . " a LEFT JOIN " . TBL_ETYPES . " b ON a.entype=b.mytype LEFT JOIN " . TBL_SECTORS . " c USING(Sectorcode) LEFT JOIN " . TBL_CRITERIA . " d USING(catid) LEFT JOIN " . TBL_CRITERIAREASON . " e USING(catcreteria) LEFT JOIN " . TBL_DISTRICTS . " f USING(Districtcode) ORDER BY a.Granteename";

            $kl = $conn->run($sql);
            $response = array();
            for ($i = 0; $p = $kl->fetch(PDO::FETCH_ASSOC); $i++) {
                $response[] = $p;
            }
        } else {
            $sql = $conn->run("SELECT " . implode(',', $args) . " FROM " . TBL_ENTITIES);
            for ($j = 0; $p = $sql->fetch(PDO::FETCH_ASSOC); $j++) {
                $response[] = $p;
            }
        }

        return $response;
    }
}

// $newRec2 = array(
//     "Granteename" => "Samuel E Bwoye",
//     "Beneficiaryno" => 0,
//     "paddress" => "P. O. Box 184 Kaliro",
//     "contperson" => "Kyambadde",
//     "contphone" => "0774830710",
//     "Districtcode" => 28,
//     "Sectorcode" => 2,
//     "fileno" => "BD1275",
//     "entype" => "sd1",
//     "pwindow" => 3,
//     "adding" => "yes",
//     "entitytypename" => "SDF Grantee",
//     "Districtname" => "Agago",
//     "Sectorcodename" => "Construction",
//     "Appbudget" => 2500000,
//     "grcont" => 1000000,
//     "startdate" => "2020-01-14",
//     "duration" => 14,
//     "dform" => "D",
//     "numroll" => 54,
//     "catid" => 0,
//     "catcreteria" => 0
// );
