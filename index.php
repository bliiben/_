<?php
/**
*
*	Controleur principal
*
*/
session_start();
ob_start();

require "classe/Road.class.php";
//Directories MVC
define("V","view/");define("C","ctrl/");define("M", "engine/");
//File for the view
define("HEADER","global/header.html");define("FOOTER","global/footer.html");
define("MENUHEADER", "global/menuHeader.html");
define("DB_NAME", "test");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");
//Global variable
	//Data base
	$PDO=new PDO('mysql:host=localhost;dbname='.DB_NAME,DB_USERNAME,DB_PASSWORD);
	//Connecté ?
	$userConnecte = (isset($_SESSION['idClient']));

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
