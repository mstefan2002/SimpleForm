<?php 
include_once('config.php');
include_once('outputApi.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
class Database
{
	private mysqli $con;
	public function __construct(private string $host, private string $username, private string $password, private string $database){}
	public function connect() : mysqli
	{
		$this->con = new mysqli($this->host, $this->username, $this->password, $this->database);

		$errorCode = $this->con->connect_error;
		if ($errorCode)
			Output::error("Connection failed: ",$errorCode);

		return $this->con;
	}
	public function checkTable() : void
	{
		$createTableQuery = "CREATE TABLE IF NOT EXISTS `".Config::$TableName."` (
			`id` INT AUTO_INCREMENT PRIMARY KEY,
			`name` VARCHAR(64) NOT NULL,
			`email` VARCHAR(100) NOT NULL UNIQUE,
			`imagePath` VARCHAR(200) NULL
		)";
        
		if (!$this->con->query($createTableQuery))
			Output::error("Error creating table: ",$this->con->error);
	}
}