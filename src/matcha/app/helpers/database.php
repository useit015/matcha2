<?php

class Database {
	private $name = DB_NAME;
	private $host = DB_HOST;
	private $port = DB_PORT;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbh;
	private $stmt;
	private $err;

	public function __construct() {
		$options = [
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];
		$dsn = 'mysql:host=' . $this->host . ';port=' . $this->port . ';dbname=' . $this->name;
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		} catch (PDOException $e) {
			die('{"error": {"text": ' . $e->getMessage() . '}}');
		}
	}

	public function query($sql) {
		$this->stmt = $this->dbh->prepare($sql);
	}

	public function bind($param, $value, $type = null) {
		try {
			if (is_null($type)) {
				switch ($true) {
					case is_int($value):
						$type = PDO::PARAM_INT;
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL;
						break;
					default:
						$type = PDO::PARAM_STR;
				}
			}
			$this->stmt->bindValue($param, $value, $type);
		} catch (PDOException $e) {
			die('{"error": {"text": ' . $e->getMessage() . '}}');
		}
	}

	public function execute($data) {
		try {
			return $this->stmt->execute($data);
		} catch (PDOException $e) {
			die('{"error": {"text": ' . $e->getMessage() . '}}');
		}
	}
	
	public function resultSet($data) {
		try {
			$this->execute($data);
			return $this->stmt->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			die('{"error": {"text": ' . $e->getMessage() . '}}');
		}
	}

	public function single($data) {
		try {
			$this->execute($data);
			return $this->stmt->fetch(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			die('{"error": {"text": ' . $e->getMessage() . '}}');
		}
	}

	public function rowCount() {
		try {
			return $this->stmt->rowCount();
		} catch (PDOException $e) {
			die('{"error": {"text": ' . $e->getMessage() . '}}');
		}
	}
}

?>
