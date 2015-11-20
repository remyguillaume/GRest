<?php
	require_once('RestService.php');
	require_once('Parameters.php');
	
	Parameters::Initialize();
	$service = new RestService();
	$service->processRequest($_SERVER, $_GET, $_POST);

?>