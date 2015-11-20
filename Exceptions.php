<?
include_once('HttpStatus.php');

class Exceptions
{
	public static function NotImplementedException() 
	{
		header('This method ist not implemented', true, HttpStatus::NotImplemented);
	}

	public static function NoDataException($object, $objectId) 
	{
		header('No data found in $object for Id=$objectId', true, HttpStatus::NoContent);
	}
	
	public static function InternalServerErrorException($msg)
	{
		header($msg, true, HttpStatus::InternalServerError);
	}
}
?>