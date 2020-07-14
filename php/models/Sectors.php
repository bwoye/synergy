<?php
// include_once "../classes/DataObject.php";
// include_once "../classes/datatables.php";

class Sectors extends DataObject{
    protected $data = array(
        "Sectorcode" => 0,
        "Sectordescription" => '',
    );
    
    public static function getMember($id){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_SECTORS." WHERE mytype = ?",[$id]);
        $row = $fr->fetch(PDO::FETCH_ASSOC);
        
        return new Sectors($row);
    }
    
    public static function getAll(){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_SECTORS);      
        //$dists = array();

        //Use this if you want to return an array of objects
        // foreach($fr->fetchAll() as $row){
        //     $dists[] = new Sectors($row);
        // }

        for($j=0;$p=$fr->fetch(PDO::FETCH_ASSOC);$j++){
            $dists[] = $p;
        } 
        return $dists;
    }
}

// $ff = Sectors::getAll();
// print_r($ff);
