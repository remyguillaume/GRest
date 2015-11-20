<?
require_once('Mapping.php');

class Parameters
{
	// Constants
	const RootPath = 'ROOT_URL'; // ! Do not forget / at the End !
	const DbName = 'DB_NAME';
	const DbServerName = 'DB_SERVER_NAME';
	const DbUserName = 'USER_NAME';
	const DbPassword = 'PASSWORD';

	// Mappings
	public static $Mappings;
	public static function Initialize()
	{
		$userMapping = new Mapping("User", "T_User");
		$userMapping->AddColumn("UserId", "Id");
		$userMapping->AddColumn("CreationDate","CreDat");
		$userMapping->AddColumn("IsAdministrator","IsAdmin");
		self::$Mappings = array(
			"User" => $userMapping
		);
	}
}
?>