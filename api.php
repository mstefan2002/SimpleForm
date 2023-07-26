<?php
	header('Content-Type: application/json');

	include('include/config.php');
	include('include/db.php');
	include('include/user.php');
	include('include/csrf.php');

	$db = new Database(Config::$DBHost,Config::$DBUserName,Config::$DBPassword,Config::$DBName);
	$con = $db->connect();
	$db->checkTable();
	

	$user = new User($con);
	if($_SERVER['REQUEST_METHOD'] === 'POST')
	{
		if(!CSRF::validateToken($_POST["csrf"]))
			Output::badRequest("invalidCSRF");
		$user->insert($_POST['name'],$_POST['email'],"image",filter_var($_POST['consent'], FILTER_VALIDATE_BOOLEAN));
		Output::created(array("csrf"=>CSRF::generateToken()));
	}
	else if($_SERVER['REQUEST_METHOD'] === 'GET')
	{
		if(isset($_GET["begin_pos"]) && isset($_GET["end_pos"]))
			Output::success($user->get(intval($_GET["begin_pos"]),intval($_GET["end_pos"])));
		else
			Output::success($user->getAll());
	}