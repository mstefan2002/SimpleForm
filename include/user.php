<?php 
include_once('validator.php');
include_once('imageController.php');
class User
{
	public function __construct(private mysqli $db) {}
	public function insert(string $name, string $email, string $imageField, bool $consent)
	{
		if(!Validator::email($email))
			Output::badRequest("invalidEmail");

		if(!Validator::name($name))
			Output::badRequest("invalidName");

		$doesImageExist = Validator::image($imageField);
		if($consent == false && $doesImageExist == true)
			Output::badRequest("consentNotGiven");
		if($consent == true && $doesImageExist == false)
			Output::badRequest("invalidImage");

		$imagePath = "";
		if($doesImageExist)
			$imagePath = ImageController::resize($imageField,500);

		$sql = "INSERT INTO ".Config::$TableName." (name, email, imagePath) VALUES (?, ?, ?)";
		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->bind_param("sss", $name, $email, $imagePath);
			$stmt->execute();
			$stmt->close();
		}
		catch(mysqli_sql_exception $e)
		{
			if($e->getCode() == 1062)
			{
				if(!empty($imagePath))
					unlink($imagePath);
				Output::badRequest("emailExists");
			}

			Output::error("Statement error ".$e->getCode());
		}
	}
	public function get(int $begin_pos, int $end_pos) : array
	{
		if($begin_pos >= $end_pos)
			Output::badRequest("beginGreater");

		if($end_pos <= 0)
			Output::badRequest("endPosNull");

		if($begin_pos < 0)
			Output::badRequest("beginPosNull");

		$rows = array();
		$num = 0;
		$sortBy = 0;
		if(isset($_GET['sortBy']))
			$sortBy = intval($_GET['sortBy']);

		$sql = "SELECT * FROM ".Config::$TableName." ";
		if($sortBy == 1)
			$sql.= "ORDER BY `name` ASC ";
		elseif($sortBy == 2)
			$sql.= "ORDER BY `email` ASC ";

		$sql.=" LIMIT ".$begin_pos.", ".($end_pos - $begin_pos);
		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$result = $stmt->get_result();

			while ($row = $result->fetch_assoc())
			{
				$row["name"] = htmlspecialchars($row["name"], ENT_QUOTES);
				$row["email"] = htmlspecialchars($row["email"], ENT_QUOTES);

				$rows[] = $row;
			}
			$stmt->close();

			$sql = "SELECT COUNT(*) as num FROM ".Config::$TableName.";";

			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$result = $stmt->get_result();

			while ($row = $result->fetch_assoc())
			{
				$num = $row["num"];
			}

			$rows[] = array("maxRows"=>$num);
		}
		catch(mysqli_sql_exception $e)
		{
			Output::error("Statement error ".$e->getCode());
		}
		
		return $rows;
	}
	public function getAll() : array
	{
		$rows = array();

		$sortBy = 0;
		if(isset($_GET['sortBy']))
			$sortBy = intval($_GET['sortBy']);

		$sql = "SELECT * FROM ".Config::$TableName." ";
		if($sortBy == 1)
			$sql.= "ORDER BY `name` ASC ";
		elseif($sortBy == 2)
			$sql.= "ORDER BY `email` ASC ";

		try
		{
			$stmt = $this->db->prepare($sql);
			$stmt->execute();
			$result = $stmt->get_result();

			while ($row = $result->fetch_assoc())
			{
				$row["name"] = htmlspecialchars($row["name"], ENT_QUOTES);
				$row["email"] = htmlspecialchars($row["email"], ENT_QUOTES);

				$rows[] = $row;
			}
			$stmt->close();
		}
		catch(mysqli_sql_exception $e)
		{
			Output::error("Statement error ".$e->getCode());
		}
		
		return $rows;
	}

}