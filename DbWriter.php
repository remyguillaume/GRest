<?
require_once("Parameters.php");

class DbWriter
{
	private $dbh;
	private $selected;
	
	public function Open() 
	{
		$this->dbh = mysql_connect(Parameters::DbServerName, Parameters::DbUserName, Parameters::DbPassword) 
			or die("Unable to connect to MySQL");
		$this->selected = mysql_select_db(Parameters::DbName, $this->dbh) 
			or die("Could not select database");
	}
	
	public function Close()
	{
		mysql_close($this->dbh);
	}
	
	public function Insert($object, $objectId, $data)
	{
		$query = "insert into $object(";
		$values = "";
		foreach ($data as $dbName => $value)
		{
			$query .= $dbName . ",";
			if (is_int($value) || is_double($value) || is_bool($value) || is_numeric($value))
				$values .= $value . ",";
			else
				$values .= "'" . $value . "',";
		}
		$query = substr($query, 0, -1);
		$values = substr($values, 0, -1);

		$query .= ") values($values)";
		
		$nbRows = mysql_query($query) or die(mysql_error()."\n".$query);
		
		if ($nbRows == 0)
		{
			Exceptions::InternalServerErrorException('Data could not be inserted.');
		}
	}
	
	public function Update($object, $objectId, $data)
	{
		$query = "update $object set ";
		foreach ($data as $dbName => $value)
		{
			$query .= $dbName . "=";
			if (is_int($value) || is_double($value) || is_bool($value) || is_numeric($value))
				$query .= $value . ",";
			else
				$query .= "'" . $value . "',";
		}
		$query = substr($query, 0, -1);

		$query .= " where Id=$objectId";
		$nbRows = mysql_query($query) or die(mysql_error()."\n".$query);
		
		if ($nbRows == 0)
		{
			Exceptions::InternalServerErrorException('Data could not be updated.');
		}
	}
	
	public function Delete($object, $objectId)
	{
		$query = "delete from $object where Id=$objectId";
		$nbRows = mysql_query($query) or die(mysql_error()."\n".$query);
		
		if ($nbRows == 0)
		{
			Exceptions::InternalServerErrorException('Data could not be deleted.');
		}
	}
}

?>