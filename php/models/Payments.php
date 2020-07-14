<?php
// include_once "DataObject.php";
// include_once "datatables.php";
//include "../../include/connector.php";
class Payments extends DataObject
{
    protected $data = array(
        "recno" => NULL,
        "fileno" => '',
        "amount" => 0,
        "datepaid" => '0000-00-00',
        "ownamount" => 0,
    );

    //private $conn = parent::connect();

    //private $response = array("error"=>true);

    public static function getPayments($fileno)
    {
        $conn = Singleton::getInstance();
        $fr = $conn->run("SELECT * FROM " . TBL_PAYMENTS . " WHERE fileno = ? ORDER BY datepaid DESC ", [$fileno]);

        for ($j = 0; $k = $fr->fetch(PDO::FETCH_ASSOC); $j++) {
            $response['payments'][] = $k;
        }

        $cc = $conn->run("SELECT perms FROM " . TBL_USERPERM . " WHERE userid=? AND mypage=?", [$_SESSION['userid'], 'budget']);

        $j = $cc->fetch();
        $response['perms'] = $j->perms;

        echo json_encode($response);
    }

    public function addPayment()
    {
        $conn = Singleton::getInstance();
        try {
            $conn->beginTransaction();
            $conn->run("INSERT INTO " . TBL_PAYMENTS . " (recno,fileno,amount,datepaid,ownamount) VALUES(:recno,:fileno,:amount,:datepaid,:ownamount)", $this->data);
            $this->data['recno'] = $conn->userInsert();            

            $fr = Entity::getMember($this->data['fileno']);

            $mv = $fr->getValueEncode("Granteename");
            //$kname = $mv[0];

            $given = new DateTime();
            $takes = $given->format('Y-m-d H:i:s');
            $conn->run("INSERT INTO " . TBL_TRANS . " VALUES(?,?,?,?)", [NULL, $_SESSION['fulname'], 'Added Payment for ' . $mv, $takes]);
            //$conn->run("INSERT INTO " . TBL_TRANS . " VALUES(?,?,?,?)", [NULL, "Gloria", 'Added Payment for ' . $kname[0], $takes]);
            $conn->commit();
            echo json_encode($this->data);
        } catch (PDOException $e) {
            $conn->rollBack();
            $response['error'] = true;
            $response['errmsg'] = "Record was not inserted";
        }
    }

    /**
     * Return one a particular payment for edit or deleting
     */
    public static function getOnePayment($recno)
    {
        $conn = Singleton::getInstance();
        $sql = $conn->run("SELECT * FROM " . TBL_PAYMENTS . " WHERE recno=:recno", ["recno" => $recno]);
        $fr = $sql->fetch(PDO::FETCH_ASSOC);
        //return new Payments($fr);

        return new  Entity($fr);      
        
    }

    public  function getName($fileno){

    }

    public function updatePayments(){
        unset($this->data['fileno']);
        $conn = Singleton::getInstance();
        
        $sql =$conn->run("UPDATE ".TBL_PAYMENTS." SET amount=:amount,ownamount=:ownamount,datepaid=:datepaid WHERE recno=:recno",$this->data); 
    }

    
    public static function getAll()
    {
        $conn = Singleton::getInstance();
        $vk = $conn->run("SELECT fileno,Granteename,Appbudget,grcont,startdate,enddate FROM " . TBL_ENTITIES . " ORDER BY Granteename");

        for ($j = 0; $bb = $vk->fetch(PDO::FETCH_ASSOC); $j++) {
            $response['ents'][] = $bb;
        }

        echo json_encode($response);
    }    
}

// $ft = array(
//     "fileno" => "w2/01/1397/2017",
//     "adding" => "yes",
//     "amount" => 1500,
//     "datepaid" => "2020-01-13",
//     "ownamount" => 200
// );
// $jj = new Budget($ft);
// //print_r($jj);
// $jj->addPayment();
