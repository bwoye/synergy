<?php

/**
 * Singleton class for making only one connection through out the application
 * Designed by Samuel E Bwoye on 15-March-2018
 */
class Singleton
{
	// General singleton class.
	// Hold the class instance.
	private static $instance = null;
	private $pdo;


	private $DBHOST = "localhost";
	private $DBUSER = "root";
	private $DBDATABASE = "synergy";
	private $DBPASS = '';
	private $salt = "synergy";


	//For upper when uploaded
	// private $DBHOST = "213.171.200.84";
	// private $DBUSER = "otimgeoff";
	// private $DBDATABASE="synergy";
	// private $DBPASS="grant07Sammy";
	// private $salt = "synergy";

	// The constructor is private
	// to prevent initiation with outer code.
	private function __construct()
	{
		// The expensive process (e.g.,db connection) goes here.

		$opt  = array(
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
			PDO::ATTR_EMULATE_PREPARES   => FALSE,
			PDO::ATTR_PERSISTENT => TRUE,
		);

		try {
			$this->pdo = new \PDO('mysql:host=' . $this->DBHOST . ';dbname=' . $this->DBDATABASE . ';charset=utf8', $this->DBUSER, $this->DBPASS, $opt);

			//echo "\nConnection successful bwoye ".$this->getDBname()."\n";            

		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	public function getDBname()
	{
		return $this->DBDATABASE;
	}

	public function getSalt()
	{
		return $this->salt;
	}

	/**
	 * to be create from within class only if no instance exists
	 */
	public static function getInstance()
	{
		if (self::$instance == null) {
			self::$instance = new Singleton;
		}

		return self::$instance;
	}

	private function __clone()
	{
	}

	public function __destruct()
	{
		$this->pdo = null;
	}

	/**
	 * Simple query builder
	 */

	public function run($sql, $args = [])
	{	
		//exit();
		if (!$args) {
			return $this->pdo->query($sql);
		}

		$stmt = $this->pdo->prepare($sql);		
		$stmt->execute($args);
		return $stmt;
	}

	public function beginTransaction()
	{
		return $this->pdo->beginTransaction();
	}

	/**
	 * What about commiting it
	 */

	public function commit()
	{
		return $this->pdo->commit();
	}

	public function rollBack()
	{
		return $this->pdo->rollBack();
	}

	/**
	 * Getting the id of the last inserted method
	 */
	public function userInsert()
	{
		return $this->pdo->lastInsertId();
	}
}
