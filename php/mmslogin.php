<?php
//session_start();
//include '../include/mypaths.php';
include_once "../php/classes/datatables.php";
include_once "../include/connector.php";
// spl_autoload_register(function ($class_name) {
//     include_once MODELS . DS . $class_name . ".php";
// });

$conn = Singleton::getInstance();

$response = array('error' => true);

//get the appbugets and display them for overall things

//count number of entities;
if (isset($_POST['others'])) {
} else {
    $cts = $conn->run("SELECT COUNT(*) FROM " . TBL_ENTITIES);
    $response['entcounts'] = $cts->fetch(PDO::FETCH_NUM)[0];

    //Work on budget now


    $myb = $conn->run("SELECT SUM(Appbudget),SUM(grcont) FROM " . TBL_ENTITIES );
    for ($j = 0; $g = $myb->fetch(PDO::FETCH_NUM); $j++) {
       $response['budget']['sdf'] = $g[0];
       $response['budget']['own'] = $g[1];
    }

    //Payments
    $vv = $conn->run("SELECT SUM(amount),SUM(ownamount) FROM ".TBL_PAYMENTS);
    for($j=0;$p=$vv->fetch(PDO::FETCH_NUM);$j++){
        $response['payment']['sdf'] = $p[0];
        $response['payment']['own'] = $p[1];
    }

    //Skilling things
    $sk = $conn->run("SELECT COUNT(*) FROM ".TBL_SKILLING);
    $response['skills']['counts'] = $sk->fetch(PDO::FETCH_NUM)[0];

    //Gender in skilling
    $gg = $conn->run("SELECT Gender,COUNT(*) FROM ".TBL_SKILLING. " GROUP BY Gender");
    for($j=0;$p=$gg->fetch(PDO::FETCH_NUM);$j++){
        if($p[0] == 'F'){
            $response['skills']['f'] = $p[1];
        }elseif($p[0] == 'M'){
            $response['skills']['m'] = $p[1];
        }else{
            $response['skills']['uncategorised'] = $p[1];
        }
    }

    //select c.Districtname as 'name',count(b.fileno) as 'nums',count(if(b.Gender='M',1,null)) as 'males',count(if(Gender='F',1,null)) as 'female',count(if(Gender='',1,null)) as 'nogen' from entities a left join skilling b using(fileno) left join districts c using(Districtcode) group by a.Districtcode;


    $km = $conn->run("SELECT c.Districtname AS 'name',COUNT(b.fileno) AS 'Total',COUNT(IF(b.Gender='M',1,null)) AS 'male',COUNT(IF(b.Gender='F',1,null)) AS 'female',COUNT(IF(b.Gender='',1,null)) AS 'nogen' FROM ".TBL_ENTITIES." a LEFT JOIN ".TBL_SKILLING." b USING(fileno) LEFT JOIN ".TBL_DISTRICTS." AS c USING(Districtcode) GROUP BY a.Districtcode ORDER BY Total DESC");

    for($j=0;$p=$km->fetch(PDO::FETCH_ASSOC);$j++){
        $response['sktable'][] = $p;
    }


    //by sector
    $mm = $conn->run("SELECT c.Sectordescription,COUNT(b.fileno) AS 'Total',COUNT(IF(b.Gender='M',1,NULL)) AS 'Male',COUNT(IF(b.Gender='F',1,NULL)) AS 'Female' FROM ".TBL_ENTITIES." a LEFT JOIN ".TBL_SKILLING." b USING(fileno) LEFT JOIN ".TBL_SECTORS." c USING(Sectorcode) WHERE a.Sectorcode <> 0 GROUP BY a.Sectorcode");

    for($j=0;$kk=$mm->fetch(PDO::FETCH_ASSOC);$j++){
        $response['secskill'][] = $kk;
    }

    $gg = $conn->run("SELECT a.pwindow,COUNT(b.fileno) AS 'Total',COUNT(IF(b.Gender='M',1,NULL)) AS 'Male',COUNT(IF(b.Gender='F',1,NULL)) AS 'Female' FROM ".TBL_ENTITIES." a LEFT JOIN ".TBL_SKILLING." b USING(fileno) WHERE a.pwindow <> 0 GROUP BY a.pwindow");

    for($j=0;$dd=$gg->fetch(PDO::FETCH_ASSOC);$j++){
        $response['pwindow'][] = $dd;
    }
}

echo json_encode($response);

/*
select a.Sectorcode,c.SectorDescription,count(b.fileno) as 'Total',count(if(b.Gender='M',1,null)) as 'Male',count(if(b.Gender='F',1,null)) as 'Female' from entities a left join skilling b using(fileno) left join sectors c using(Sectorcode) where a.Sectorcode <>0 group by a.Sectorcode order by Female;
+------------+-------------------+-------+-------+--------+
| Sectorcode | SectorDescription | Total | Male  | Female |
+------------+-------------------+-------+-------+--------+
|          2 | Construction      |  1256 |   936 |    320 |
|          3 | Manufacturing     |  7165 |  3017 |   4103 |
|          1 | Agriculture       | 32784 | 17603 |  15068 |
+------------+-------------------+-------+-------+--------+

select a.pwindow,count(b.fileno),count(if(b.Gender='M',1,null)) as 'Male',count(if(b.Gender='F',1,null)) as 'Female' from entities a left join skilling b using(fileno) where a.pwindow<>0 group by a.pwindow;
+---------+-----------------+-------+--------+
| pwindow | count(b.fileno) | Male  | Female |
+---------+-----------------+-------+--------+
|       2 |           31899 | 14893 |  16888 |
|       1 |            7536 |  5894 |   1607 |
|       3 |             348 |   215 |    133 |
+---------+-----------------+-------+--------+
*/