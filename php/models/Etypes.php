<?php


class Etypes extends DataObject{
    protected $data = array(
        "mtype" => 0,
        "tname" => '',
    );

    private $response=array();
    private $conn;

    public function __construct()
    {
        $this->conn = parent::connect();
        parent::__construct($this->data);
    }
    
    public static function getMember($id){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_ETYPES." WHERE mytype = ?",[$id]);
        $row = $fr->fetch(PDO::FETCH_ASSOC);
        
        return new Etypes($row);
    }
    
    public static function getAll(){
        $conn = parent::connect();
        $fr = $conn->run("SELECT * FROM ".TBL_ETYPES);      
        $dists = array();

        // foreach($fr->fetchAll() as $row){
        //     $dists[] = new Etypes($row);
        // }

        for($j=0;$p=$fr->fetch(PDO::FETCH_ASSOC);$j++){
            $dists[] = $p;
        }
        return $dists;
    }

    public function addItem(){
        $sql = $this->conn->run("INSERT INTO ".TBL_ETYPES." VALUES(:mytype,:tname)",$this->data);
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


