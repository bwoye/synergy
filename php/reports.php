<?php
include_once "../include/mypaths.php";
include_once "../php/makefilefunctions.php";

spl_autoload_register(function ($class_name) {
    include MODELS . DS . $class_name . ".php";
});

$response = array();
$conn = Singleton::getInstance();

if (isset($_POST['grantees'])) {
    $mk = $conn->run("SELECT a.*,DATE(a.startdate) as sdate,DATE(a.enddate) as edate,b.Districtname as district,c.Sectordescription as sector FROM " . TBL_ENTITIES . " a LEFT JOIN " . TBL_DISTRICTS . " b USING(districtcode) LEFT JOIN " . TBL_SECTORS . " c  USING(Sectorcode) ORDER BY a.Granteename");
    for ($j = 0; $k = $mk->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['entities'][] = $k;
    }

    //Now in the grantees also put skilling things for each
    //General query by skilling and drop out
    /*
     select fileno,COUNT(*) as 'Enrolment',COUNT(if(Gender ='F',1,null)) as 'Female',COUNT(if(Gender='F' && mact='co',1,null)) as 'Fco',COUNT(if(Gender='F' && mact='tr',1,null)) as 'Ftr',COUNT(if(Gender='F' && mact='dr',1,null)) as 'Fdr',COUNT(if(Gender='M',1,null)) as 'Male',COUNT(if(Gender='M' && mact='co',1,null)) as 'Mco',COUNT(if(Gender='M' && mact='tr',1,null)) as 'Mtr',COUNT(if(Gender='M' && mact='dr',1,null)) as 'Mdr' from skilling group by fileno
     */

     $vk = $conn->run("SELECT a.Granteename,COUNT(IF(b.Gender ='F',1,NULL)) as 'Female',COUNT(IF(b.Gender='F' && b.mact='co',1,NULL)) as 'Femcompleted',COUNT(IF(b.Gender='F' && mact='tr',1,NULL)) as 'Femtrain',COUNT(IF(b.Gender='F' && b.mact='dr',1,NULL)) as 'Femdrop',COUNT(IF(b.Gender='M',1,NULL)) as 'Male',COUNT(IF(b.Gender='M' && b.mact='co',1,NULL)) as 'Mco',COUNT(IF(b.Gender='M' && b.mact='tr',1,NULL)) as 'Mtr',COUNT(IF(b.Gender='M' && b.mact='dr',1,NULL)) as 'Mdropped' FROM ".TBL_ENTITIES." a LEFT JOIN ".TBL_SKILLING." b USING(fileno) GROUP BY a.fileno ORDER BY a.Granteename DESC");

     for($j=0;$mk = $vk->fetch(PDO::FETCH_ASSOC);$j++){
         $response['skillsums'][] = $mk;
     }


} else if (isset($_POST['districts'])) {
    $pd = $conn->run("SELECT * FROM " . TBL_DISTRICTS);
    for ($j = 0; $p = $pd->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['dists'][] = $p;
    }
} elseif (isset($_POST['skillarea'])) {

    $sa = $conn->run("SELECT * FROM " . TBL_SKILLAREA);
    for ($j = 0; $kl = $sa->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['skillarea'][] = $kl;
    }
} elseif (isset($_POST['skilling'])) {

    $sk = $conn->run("SELECT * FROM " . TBL_SKILLING);
    for ($j = 0; $mk = $sk->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['skilling'][] = $mk;
    }
} else if (isset($_POST['payments'])) {

    $sp = $conn->run("SELECT * FROM " . TBL_PAYMENTS);
    for ($j = 0; $g = $sp->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['payments'][] = $sp;
    }
} elseif (isset($_POST['mskilling'])) {
    //Do overall on skilling
    $pr = $conn->run("SELECT if(mact='dr','Droped out',if(mact='co','Completed','Training')) AS 'Status',COUNT(IF(Gender='F',1,NULL)) AS 'Female',COUNT(IF(Gender='M',1,NULL)) AS 'Male' FROM " . TBL_SKILLING . " GROUP BY mact;");

    for ($j = 0; $p = $pr->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['overall'][] = $p;
    }

    //Do by sector
    $pm = $conn->run("select a.Sectorcode,COUNT(if(b.mact='co',1,null)) as 'Completed',COUNT(if(b.mact='tr',1,null)) as 'Trainig',COUNT(if(mact='dr',1,null)) as 'Droped Out',c.Sectordescription from entities a left join skilling b using(fileno) left join sectors c using(Sectorcode) where Gender <>'' and a.Sectorcode <> 0 group by a.Sectorcode");

    for ($j = 0; $m = $pm->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['mysectors'][] = $m;
    }

    //By district
    $mm = $conn->run("select a.Districtcode,d.Districtname AS 'District',COUNT(if(b.mact='co',1,null)) as 'Completed',COUNT(if(b.mact='tr',1,null)) as 'Trainig',COUNT(if(mact='dr',1,null)) as 'Droped Out'  from entities a left join skilling b using(fileno) left join districts d using(Districtcode) group by a.Districtcode order by District");

    for ($j = 0; $k = $mm->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['mydists'][] = $k;
    }

    //By window

    $kl = $conn->run("SELECT a.pwindow,COUNT(IF(b.Gender='F',1,NULL)) as 'Female',COUNT(IF(b.Gender='M',1,NULL)) as 'Male' FROM " . TBL_ENTITIES . " a LEFT JOIN " . TBL_SKILLING . " b USING(fileno) GROUP BY a.pwindow");

    for ($j = 0; $g = $kl->fetch(PDO::FETCH_ASSOC); $j++) {
        $response['bywindow'][] = $g;
    }
}
echo json_encode($response);
