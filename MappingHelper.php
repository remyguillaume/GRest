<?
require_once("Parameters.php");

class MappingHelper
{
	public static function GetMapping($model) 
	{
		foreach (Parameters::$Mappings as $key => $value)
		{
			if ($key == $model)
				return $value;
		}
		
		return null;
	}
}

?>