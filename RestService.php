<?
require_once("Exceptions.php");
require_once("Parameters.php");
require_once("UrlHelper.php");
require_once("MappingHelper.php");
require_once('HttpStatus.php');
require_once('DbReader.php');
require_once('DbWriter.php');

class RestService 
{
  public function processRequest($_SERVER, $_GET, $_POST) 
  {
    $url = UrlHelper::getFullUrl($_SERVER);
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) 
    {
      case 'GET':
      case 'HEAD':
        $arguments = $_GET;
        break;
      case 'POST':
        $arguments = $_POST;
        break;
      case 'PUT':
      case 'DELETE':
        parse_str(file_get_contents('php://input'), $arguments);
        break;
    }
    $accept = $_SERVER['HTTP_ACCEPT'];
    $this->handleRequest($url, $method, $arguments, $accept);
  }

  public function handleRequest($url, $method, $arguments, $accept) 
  {
    switch($method) 
	{
      case 'GET':
        $this->performGet($url, $arguments, $accept);
        break;
      case 'HEAD':
        $this->performHead($url, $arguments, $accept);
        break;
      case 'POST':
        $this->performPost($url, $arguments, $accept);
        break;
      case 'PUT':
        // Decode arguments
	$json = key($arguments);
	$json = str_replace("\\", "", $json);
	$object = json_decode($json);
	$tab = (array)$object;
	// Call PUT
        $this->performPut($url, $tab, $accept);
        break;
      case 'DELETE':
        $this->performDelete($url, $arguments, $accept);
        break;
      default:
        Exceptions::NotImplementedException();
    }
  }

	public function performGet($url, $arguments, $accept) 
	{
		// Decode url
		UrlHelper::Decode($url, $model, $objectId);
		
		// Get Mapping
		$mapping = MappingHelper::GetMapping($model);
		if ($mapping != null)
			$object = $mapping->DbTableName;
		else
			$object = $model;
		
		// Read data from DB
		$dbReader = new DbReader();
		$dbReader->Open();
		$result = $dbReader->Read($object, $objectId);
		$dbReader->Close();
		
		if ($result == null)
		{
			// no data found
			Exceptions::NoDataException($object, $objectId);
		}
		else
		{
			$json = null;
			foreach ($result as $key => $row)
			{
				// Map names
				if ($mapping != null)
					$data = $mapping->MapDbToModel($row);
				else
					$data = $row;

				// Encode data using json
				if ($json != null)
					$json .= ",";
				$json .= json_encode($data);
			}
			
			if (count($result) > 1)
				$json = "[" . $json . "]";
			
			// Write data to output
			header('Content-type: application/json; charset=utf-8');
			header('', true, HttpStatus::OK);
			echo utf8_encode($json);
		}
	}

	public function performPut($url, $arguments, $accept) 
	{
		// Decode url
		UrlHelper::Decode($url, $model, $objectId);
		
		// Get Mapping
		$mapping = MappingHelper::GetMapping($model);
		if ($mapping != null)
			$object = $mapping->DbTableName;
		else
			$object = $model;

		// Map names
		if ($mapping != null)
			$data = $mapping->MapModelToDb($arguments);
		else
			$data = $arguments;

		// Read data from DB
		$dbReader = new DbReader();
		$dbReader->Open();
		$result = $dbReader->Read($object, $objectId);
		$dbReader->Close();
		
		// Insert or Update
		$dbWriter = new DbWriter();
		$dbWriter->Open();
		if ($result == null)
		{
			// no data found => we make an insert
			$dbWriter->Insert($object, $objectId, $data);
		}
		else
		{
			// something found => we make an update
			$dbWriter->Update($object, $objectId, $data);
		}
		$dbWriter->Close();

		// Read just inserted data from DB, and return it.
		$this->performGet($url, $arguments, $accept);
	}
	
	public function performPost($url, $arguments, $accept) 
	{
		Exceptions::NotImplementedException();
	}
	
	public function performHead($url, $arguments, $accept) 
	{
		Exceptions::NotImplementedException();
	}

	public function performDelete($url, $arguments, $accept) 
	{
		// Decode url
		UrlHelper::Decode($url, $model, $objectId);

		// Get Mapping
		$mapping = MappingHelper::GetMapping($model);
		if ($mapping != null)
			$object = $mapping->DbTableName;
		else
			$object = $model;

		// Delete
		$dbWriter = new DbWriter();
		$dbWriter->Open();
		$dbWriter->Delete($object, $objectId);
		$dbWriter->Close();
	
		header('Content-type: application/json; charset=utf-8');
		header('', true, HttpStatus::OK);
	}
}
?>