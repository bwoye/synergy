<?php
/**
 * Singleton clas for making only one connection through out the application
 * Designed by Samuel E Bwoye on 15-March-2018
 */

//  include "../../include/connector.php";
//  include "../classes/datatables.php";
//  include "../classes/DataObject.php";

class Skilling extends DataObject{
    protected $data = array(
        "recno" => 0,
        "fulname" => '',
        "Nationalidno" => '',
        "fileno" => '',
        "Skillcode" => 0,
        "Gender" => '',
        "Phonecontact" => '',
        "email" => '',
        "mact" => '',
        "bknowtool" => 0,
        "busetool" => 0,
        "bapptool" => 0,
        "mknowtool" => 0,
        "musetool" => 0,
        "mapptool" => 0,
        "eknowtool" => 0,
        "eusetool" => 0,
        "eapptool" => 0,
        "bline" => 0,
        "mline" => 0,
        "eline" => 0,
        "newval" => 0,
        "selbline" =>0,
        "selmline" => 0,
        "seleline" => 0,
    );
    
    public static function getMember($id){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_SKILLING." WHERE recno =:recno",["recno"=>$id]);
        $row = $fr->fetch(PDO::FETCH_ASSOC);
        
        return new Skilling($row);
    }

    public static function getForCompany($fileno){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_SKILLING." WHERE fileno=:fileno ORDER BY fulname",["fileno"=>$fileno]);      
        //$dists = array();

        $response = array("Skilling"=>"This is the base table");

        for($j=0;$k=$fr->fetch(PDO::FETCH_ASSOC);$j++){
            $response[] = $k;
        }

        return $response;
    
    }
    
    public static function getAll(){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_SKILLING);      
        $dists = array();

        // foreach($fr->fetchAll() as $row){
        //     $dists[] = new Skilling($row);
        // }

        for($j=0;$gh=$fr->fetch(PDO::FETCH_ASSOC);$j++){
            $dists[] = $gh;
        }
        //return $dists;
        header("Content-Type:Application/json");
        echo json_encode($dists);

    }

    /**
     * $myarr is the list of fields in an array
     * $fileno is the file no of the company
     * $name is the name of the response array
     */
    public static function selectFew($myarr=[],$fileno){
        //$response=array();
        $conn=parent::connect();
        $fr = $conn->run("SELECT ".implode(",",$myarr)." FROM ".TBL_SKILLING." WHERE fileno=:fileno",["fileno"=>$fileno]);
        for($i=0;$v=$fr->fetch(PDO::FETCH_ASSOC);$i++){
            $response[]=$v;
        }
        return json_encode($response);
    }

    public static function getGenderByDistrict($Districtcode){
        $conn=parent::connect();
        $sql = $conn->run("SELECT mact,Gender,COUNT(*) FROM ".TBL_SKILLING." WHERE fileno in (SELECT fileno FROM ".TBL_ENTITIES." WHERE Districtcode=:Districtcode) GROUP BY Gender,mact ",["Districtcode"=>$Districtcode]);
        return self::getMegenderArray($sql);
    }

    public static function getGenderByEntity($fileno){
        $conn = parent::connect();
        $sql = $conn->run("SELECT mact,Gender,COUNT(*) FROM ".TBL_SKILLING." WHERE fileno=:fileno GROUP BY Gender,mact",["fileno"=>$fileno]);

       return self::getMegenderArray($sql);
    }

    public static function getGnderBySize($idcat){

    }

    public static  function getMegenderArray($sql){        
        for($j=0;$p=$sql->fetch(PDO::FETCH_NUM);$j++){
            $response[$p[0]][$p[1]] = $p[2];
        }       
        return $response;
    }

    //W2/1/743/2017
}

//Skilling::getGenderByDistrict(3);