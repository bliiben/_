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
require "classe/Test.interface.php";
//Global variable
	//Data base
	$PDO=new PDO('mysql:host=localhost;dbname='.DB_NAME,DB_USERNAME,DB_PASSWORD);
	//Connecté ?
	$ERRORS=array();
	//CODE MENU
	$CODEMENU = 0;
	//User connecté ?
	$USERCONNECTE = isset($_SESSION['idUser']);

try{
	//Try to connect the user by uuid
	//do it once when the user come
	if( ! $USERCONNECTE && ( ( isset($_SESSION['tried']) && $_SESSION['tried'] != false ) || !isset($_SESSION['tried']) ) )
	{
		new Road("user","connexionByUUID");
		$_SESSION['tried']=true;
	}
	if (!( isset( $_GET['action']) && isset( $_GET['module']) && !empty($_GET['module']) && !empty($_GET['action'])))
	{
		if( ! isset( $_GET['action']) && ! isset( $_GET['module']) )
		{
			new Road("accueil","presentation");
		}
		else
		{
			throw new Exception("Des éléments sont manquant pour trouver la page", 1);
		}
	}else
	{
		$r = new Road($_GET['module'],$_GET['action']);
	}

}catch(Exception $e){

	$ERRORS[] = new Error($e->getMessage());
	include(HEADER);
	include(FOOTER);
}
ob_end_flush();
