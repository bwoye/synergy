<?php

class Idcat extends DataObject{
    protected $data = array(
        "catid" => NULL,
        "description" => ''
    );

    public static function getFullTable(){
        $conn = parent::connect();
        $pv = $conn->run("SELECT * FROM ".TBL_CRITERIA." ORDER BY description");
        //$response = array();
        for($j=0;$m=$pv->fetch(PDO::FETCH_ASSOC);$j++){
            $response[$j] = $m;
        }

        return $response;
    }
    
}

// $ff = Districts::getAll();
// print_r($ff);
// echo "<h2>".$g=Districts::getMember(16)->getValue("Districtname")."</h2>";
