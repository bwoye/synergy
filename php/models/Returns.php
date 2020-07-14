<?php
// include_once "../classes/DataObject.php";
// include_once "../classes/datatables.php";

class Returns extends DataObject{
    protected $data = array(
        "idno" => NULL,
        "fileno" => '',
        "retmonth" => 0,
        "retyear" => 0,
    );
    
   
    public static function getAll($fileno){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_RETURNS. " WHERE fileno=:fileno ORDER BY retyear,retmonth DESC",["fileno"=>$fileno]);      
        //$dists = array();

        //Use this if you want to return an array of objects
        // foreach($fr->fetchAll() as $row){
        //     $dists[] = new Sectors($row);
        // }

        for($j=0;$p=$fr->fetch(PDO::FETCH_ASSOC);$j++){
            $myreturns[] = $p;
        } 
        return $myreturns;
    }

    public function addReturn(){
        //Check to see if someone is not filing twice
        $this->data['idno'] = NULL;

        $many = $this->data;
        unset($many['idno']);
        $conn = parent::connect();
        $sql = $conn->run("SELECT * FROM ".TBL_RETURNS." WHERE fileno=:fileno AND retmonth=:retmonth AND retyear=:retyear",$many);

        if($sql->rowCount() > 0){
            $response['error'] = true;
            $response['errmsg'] = "Return already filed";
            return $response;
        }

        $nRet = $conn->run("INSERT INTO ".TBL_RETURNS. "(idno,fileno,retmonth,retyear) VALUES(:idno,:fileno,:retmonth,retyear)",$this->data);


        $sql = $conn->run("SELECT * FROM ".TBL_RETURNS." WHERE fileno=:fileno AND retmonth=:retmonth AND retyear=:retyear",$many);

        for($j=0;$p=$sql->fetch(PDO::FETCH_ASSOC);$j++){
            $newRet[] = $p;
        }
    }
}

// $ff = Sectors::getAll();
// print_r($ff);
