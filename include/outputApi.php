<?php

class Output
{
	public static function error(string $errorName,int $errorCode=-1) : void
	{
		$error = array(
				'message' => 'We encountered an error. Please try again later.',
				'error' => $errorCode
			);
		if($errorCode == -1)
			error_log($errorName);
		else
			error_log($errorName . $errorCode);
		http_response_code(500);
		echo json_encode($error);
		exit();
	}
	public static function badRequest(string $message)
	{
		$data = array('message' => $message);
		http_response_code(400);
		echo json_encode($data);
		exit();
	}
	public static function created(?array $arrOutput=null)
	{
		http_response_code(201);
		$jsonOutput = "[]";
		if(isset($arrOutput) && is_array($arrOutput))
			$jsonOutput = json_encode($arrOutput);
		echo $jsonOutput;
		exit();
	}
	public static function success(?array $arrOutput=null)
	{
		http_response_code(200);
		$jsonOutput = "[]";
		if(isset($arrOutput) && is_array($arrOutput))
			$jsonOutput = json_encode($arrOutput);
		echo $jsonOutput;
		exit();
	}

}