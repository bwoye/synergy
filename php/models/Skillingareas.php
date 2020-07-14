<?php
//include "../../include/mypaths.php";
class Skillingareas extends DataObject
{
    protected  $data = array(
        "skillcode" => 0,
        "Skilldescription" => ''
    );

    public static function skillarea($mycode)
    {
        $conn = Singleton::getInstance();

        $sql = $conn->run("SELECT * FROM " . TBL_SKILLAREA . " WHERE skillcode=:mycode", ["mycode" => $mycode]);
        return new Skillingareas($sql->fetch(PDO::FETCH_ASSOC));
    }

    public static function getAll(){
        $response=array("error"=>false);
        $conn = Singleton::getInstance();
        $sql = $conn->run("SELECT * FROM ".TBL_SKILLAREA);

        for($k=0;$v=$sql->fetch(PDO::FETCH_ASSOC);$k++){
            $response[] = $v;
        }
        return $response;
    }
    public static function getFullTable(){
        //$response=array("error"=>false);
        $conn = parent::connect();
        $sql = $conn->run("SELECT * FROM ".TBL_SKILLAREA);

        for($k=0;$v=$sql->fetch(PDO::FETCH_ASSOC);$k++){
            $response[] = $v;
        }
        return $response;
    }

    public function editSkills($newEditedSkill)
    {
        $this->data['Skilldescription'] = $newEditedSkill;
        $this->saveEdits();
    }

    private function saveEdits()
    {
        $conn = parent::connect();
        $sql = $conn->run("UPDATE " . TBL_SKILLAREA . " SET Skilldescription=:Skilldescription WHERE skillcode=:skillcode",$this->data);
        $response['error'] = false;
        $response['errmsg'] = "Record was updated";
        echo json_encode($response);
    }

    public function addNewSkillarea()
    {
        $conn = Singleton::getInstance();
        try {
            $sql = $conn->run("INSERT INTO " . TBL_SKILLAREA . " VALUES(:mycode,:skills", ["mycode" => NULL, "skills" => $this->data['skildescription']]);
            $this->data['skillcode'] = $conn->userInsert();
           
            $response['skillcode'] = $this->data['skillcode'];
            $response['skilldescription'] = $this->data['skilldescription'];
            $response['error'] = false;
            $response['errmsg'] = "Record was inserted";
            $conn->commit();
            echo json_encode($response);
        } catch (PDOException $e) {
            $conn->rollBack();
            $response['error'] = true;
            $response['errmsg'] = $e->getMessage();
        }
    }   
}


