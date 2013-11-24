<?php

/**
* GRUD user
*/
class UserGRUD
{
	private $PDO;
	function __construct()
	{
		global $PDO;
		$this->PDO = $PDO;
	}

	public function inscrireUser($pseudo,$password,$firstname,$lastname,$email )
	{
		global $ERRORS;
			
		$st = $this->PDO->prepare("INSERT INTO user SET pseudo=:pseudo,password=:password,email=:email,firstname=:firstname,lastname=:lastname");

		$st->execute(array(	'pseudo'=> $pseudo
									,'password'=> crypt($password)
									,'email'=> $email
									,'firstname'=> $firstname
									,'lastname'=> $lastname
							));
	}
	/* Return the idUser if the pseudo ant password are good else false */
	public function connexionUser($pseudo,$password)
	{
		$_SESSION['connexionByUUID'] = false;
		$st = $this->PDO->prepare("SELECT idUser, password FROM user WHERE pseudo=?");
		$st->execute(array($pseudo));
		while($d=$st->fetch()){
			if((crypt($password,$d['password']) == $d['password']))
				return $d['idUser'];
		}
		return false;
	}
	//@return idUser of the pseudo
	//Not use atm
	public function getIdOf($pseudo)
	{
		$st = $this->PDO->prepare("SELECT idUser FROM user WHERE pseudo=?");
		$st->execute(array($pseudo));
		while($d=$st->fetch()){
			return $d['idUser'];
		}
		throw new Exception("Sorry user do not exist !", 1);
		
	}
	//Save the uuid of the user for future connexion
	public function registerUUIDToUser($pseudo)
	{
		$uuid = uniqid("",true);//Generate a uuid
		setcookie("uuid",$uuid,time()+7*3600*24,'/');
		setcookie("pseudo",$_POST['pseudo'],time()+7*3600*24,'/');

		$st=$this->PDO->prepare("UPDATE user set uuid=:uuid WHERE pseudo=:pseudo");
		$st->execute(array('pseudo'=>$pseudo,'uuid'=>$uuid));

	}
	//Return true if user can connect
	public function connexionByUUID()
	{
		$_SESSION['connexionByUUID'] = true;
		if(isset($_COOKIE['uuid']) && isset($_COOKIE['pseudo'])){
			$st = $this->PDO->prepare("SELECT idUser FROM user WHERE pseudo=:pseudo AND uuid=:uuid ");
			$st->execute(array("pseudo"=>$_COOKIE['pseudo'],"uuid"=>$_COOKIE['uuid']));
			while($d=$st->fetch()){
				return $d['idUser'];
			}
		}
		return false;
	}
	public function deconnexion()
	{
		if($GLOBALS['USERCONNECTE'])
		{
			if($_SESSION['connexionByUUID'])
			{
				//supprime sa connection auto car il s'être connecté par uuid
				$this->PDO->exec("UPDATE user SET uuid='' WHERE idUser=$idUser ");
			}
			session_destroy();
		}
		else{
			// That ain't normal !! Hack
			/**
			* Déconnexion d'un user non connecté
			*/
		}
	}
	//return true is all is ok and the user is saved in the session or throw an exception if something goes wrong
	public function saveUserInSession($idUser)
	{
		$_SESSION['idUser'] = $idUser;
		$st=$this->PDO->query("SELECT * FROM user WHERE idUser=$idUser");
		foreach ($st as $row) {
			$_SESSION['pseudo'] = $row['pseudo'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['firstname'] = $row['firstname'];
			$_SESSION['lastname'] = $row['lastname'];
			return true;
		}
		throw new Exception("Sorry user do not exist", 1);
		
	}
}
