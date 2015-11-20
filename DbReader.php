<?
require_once("Parameters.php");

class DbReader
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
	
	public function Read($object, $objectId)
	{
		$query = "select * from $object";
		if ($objectId != "*")
			$query .= " where Id=$objectId";
		
		$result = mysql_query($query) or die(mysql_error()."\n".$query);
		
		$data = array();
		$nbResult = 0;
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
		{
			$nbResult++;
			$data[$row["Id"]] = $row;
		}
		
		if ($nbResult > 0)
			return $data;
		
		return null;
	}
}

?>