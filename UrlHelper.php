<?
require_once("Parameters.php");

class UrlHelper
{
  public static function getFullUrl($_SERVER) 
  {
    $protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
    $location = $_SERVER['REQUEST_URI'];
    if ($_SERVER['QUERY_STRING']) 
	{
      $location = substr($location, 0, strrpos($location, $_SERVER['QUERY_STRING']) - 1);
    }
	
    return $protocol.'://'.$_SERVER['HTTP_HOST'].$location;
  }
  
	public static function Decode($url, &$object, &$objectId)
	{
		$index = strlen(Parameters::RootPath);
		$paramString = substr($url, $index);
		$params = explode('/', $paramString);
		
		$object = $params[0];
		$objectId = $params[1];
		if ($objectId == null || $objectId == "")
			$objectId = "*";
	}
}

?>