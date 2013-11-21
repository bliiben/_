<?php
/**
* coucou
*/
class Client
{
	private $PDO;
	function __construct()
	{
		//May load Engine Classes
		global $PDO;
		$this->PDO = $PDO;
	}
	function liste()
	{
		$client = $this->PDO->query("SELECT * FROM client");
		include(V."listeClient.php");
	}
	function ajouter()
	{
		if(isset($_GET['nom']) && isset($_GET['prenom']))
		{
			$st = $this->PDO->prepare("INSERT INTO client SET nom=?,prenom=?");
			$st->execute(array($_GET['nom'],$_GET['prenom']));
			$this->liste();
		}
		else
		{
			throw new Exception("Missing informations", 1);
		}
	}
	function supprimer()
	{
		if(isset($_GET['idClient']) && is_numeric($_GET['idClient']))
		{
			$st = $this->PDO->prepare("DELETE FROM client WHERE idClient=?");
			$st->execute(array($_GET['idClient']));
			$this->liste();
		}
		else
		{
			throw new Exception("Missing informations", 1);
		}
	}
}
