<?php

class CSRF
{
	public static function generateToken() : string
	{
		$token = bin2hex(random_bytes(32));
		$_SESSION['csrf_token'] = $token;
		return $token;
	}
	public static function getToken() : string
	{
		if(!isset($_SESSION['csrf_token']))
			return self::generateToken();
		return $_SESSION['csrf_token'];
	}
	public static function validateToken($token) : bool
	{
    		return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
	}
}