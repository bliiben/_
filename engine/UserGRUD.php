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
	/* Return if the pseudo ant password are good */
	public function connexionUser($pseudo,$password)
	{
		$st = $this->PDO->prepare("SELECT idUser, password FROM user WHERE pseudo=?");
		$st->execute(array($password));
		while($d=$st->fetch()){
			echo $d['password'];
		}
	}
}
