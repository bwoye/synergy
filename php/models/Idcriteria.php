<?php

class Idcriteria extends DataObject{
    protected $data = array(
        "catcreteria" => NULL,
        "description" => ''
    );

    private $conn;
    private $response=array();

    public function __construct()
    {
        $this->conn = parent::connect();
        parent::__construct($this->data);
    }

    public static function getFullTable(){
        $conn = parent::connect();
        $pv = $conn->run("SELECT * FROM ".TBL_CRITERIAREASON." ORDER BY description");
        //$response = array();
        for($j=0;$m=$pv->fetch(PDO::FETCH_ASSOC);$j++){
            $response[$j] = $m;
        }

        return $response;
    }

    public function addItem(){
        $this->data['idcreteria'] = null;
        $sql = $this->conn->run("INSERT INTO ".TBL_CRITERIAREASON." VALUES(:catcreteria,:description)",$this->data);
        if($sql){
            $this->response['error'] = false;
            $this->response['errmsg'] = "Record was added";
        }else{
            $this->response['error'] = true;
            $this->response['errmsg'] = "Record was not added";
        }
        return $this->response;
    }
    
}

// $ff = Districts::getAll();
// print_r($ff);
// echo "<h2>".$g=Districts::getMember(16)->getValue("Districtname")."</h2>";
