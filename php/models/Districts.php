<?php

class Districts extends DataObject{
    protected $data = array(
        "Districtcode" => NULL,
        "Districtname" => '',
        "Region" => ''
    );
    
    public static function getMember($Districtcode){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_DISTRICTS." WHERE Districtcode = ?",[$Districtcode]);
        $row = $fr->fetch(PDO::FETCH_ASSOC);
        
        return new Districts($row);
    }
    
    public static function getAll(){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_DISTRICTS);      
        $dists = array();

        foreach($fr->fetchAll() as $row){
            $dists[] = new Districts($row);
        }
        return $dists;
    }

    public static function getFullTable(){
        $conn = parent::connect();
        $sql = $conn->run("SELECT * FROM ".TBL_DISTRICTS." ORDER BY districtname");
        $response = array();
        for($j=0;$v=$sql->fetch(PDO::FETCH_ASSOC);$j++){
            $response[] = $v;
        }
        return $response;
    }

    public function addDistrict(){
        $conn = parent::connect();
        //$this->data['Districtcode'] = NULL;
        $sql = $conn->run("INSERT INTO ".TBL_DISTRICTS." (Districtcode,Districtname,Region) VALUES(:Districtcode,:Districtname,:Region)",$this->data);
    } 
}

// $ff = Districts::getAll();
// print_r($ff);
// echo "<h2>".$g=Districts::getMember(16)->getValue("Districtname")."</h2>";
