<?php
/**
*
*	Controleur principal
*
*/
session_start();
ob_start();

require "define.php";
require "classe/Road.class.php";
require "classe/Error.class.php";

//Global variable
	//Data base
	$PDO=new PDO('mysql:host=localhost;dbname='.DB_NAME,DB_USERNAME,DB_PASSWORD);
	//Connecté ?
	$ERRORS=array();
if ( isset( $_GET['action']) && isset( $_GET['module']) && !empty($_GET['module']) && !empty($_GET['action']))
{
	$r = new Road($_GET['module'],$_GET['action']);
}
else
{
	//404
	header("HTTP/1.0 404 Not Found");
	//echo "I'm sorry you're lost";
	include(HEADER);
	include(FOOTER);
}
ob_end_flush();
