<?
class Mapping
{
	public function __construct($modelTableName, $dbTableName) 
	{
		$this->ModelTableName = $modelTableName;
		$this->DbTableName = $dbTableName;
	}
	
	public $ModelTableName;
	public $DbTableName;
	
	public $ModelToDbColumns = array();
	public $DbToModelColumns = array();
	
	public function AddColumn($modelName, $dbName)
	{
		$this->ModelToDbColumns[$modelName] = $dbName;
		$this->DbToModelColumns[$dbName] = $modelName;
	}
	
	public function MapDbToModel($row)
	{
		$data = array();
		foreach ($row as $dbName => $value)
		{
			if (array_key_exists($dbName, $this->DbToModelColumns))
			{
				$data[$this->DbToModelColumns[$dbName]] = $value;
			}
			else
			{
				$data[$dbName] = $value;
			}
		}
		
		return $data;
	}
	
	public function MapModelToDb($data)
	{
		$row = array();
		foreach ($data as $modelName => $value)
		{
			if (array_key_exists($modelName, $this->ModelToDbColumns)) 
			{
				$row[$this->ModelToDbColumns[$modelName]] = $value;
			}
			else
			{
				$row[$modelName] = $value;
			}
		}
		
		return $row;
	}
}
?>