<?php
//include_once "../../include/connector.php";


abstract class DataObject
{
    protected $data = array();

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data))
                $this->data[$key] = $value;
        }
        //echo "Object created";
    }

    public function getValue($field)
    {
        if (array_key_exists($field, $this->data))
            return $this->data[$field];
        else
            die("Field not found");
    }

    public function getValueEncode($field)
    {
        return htmlspecialchars($this->getValue($field));
    }

    protected static function connect()
    {
        $conn = Singleton::getInstance();
        return $conn;
    }

    protected static function countRecords($table, $args = [])
    {
        $conn = Self::connect();
        $rt = "";
        if (!$args) {
            $rt = $conn->run("SELECT COUNT(*) FROM " . $table);
        } else {
            if (count($args) == 2) {
                $rt = $conn->run("SELECT COUNT(*) FROM " . $table . " WHERE " . $args[0] . "=?",[$args[1]]);
                //Return something here man
            }else{
                return 0;
            }
        }

        $kl = $rt->fetch(PDO::FETCH_NUM)[0];
        return $kl;
    }
}
