
<?php

class Translog extends DataObject{
    protected $data =array(
        "recno"=>0,
        "fulname"=>'',
        "action"=>'',
        "mydate"=>'0000-00-00'
    );

    public function addItem(){
        $this->data['recno'] =null;

        $conn = Singleton::getInstance();
        $sql = $conn->run("INSERT INTO ".TBL_TRANS." VALUES(:recno,:fulname,:action,:mydate)",$this->data);
    }
}