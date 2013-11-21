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
									,'password'=> $password
									,'email'=> $email
									,'firstname'=> $firstname
									,'lastname'=> $lastname
							));
	}
}
