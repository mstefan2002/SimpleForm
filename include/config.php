<?php 
session_start();
ini_set("display_errors", "0");
class Config
{
	public static string $DBHost = "";
	public static string $DBUserName = "";
	public static string $DBPassword = "";
	public static string $DBName = "";

	public static string $TableName = "user";
}