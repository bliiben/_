<?php
/**
* User class
*/
class User
{
	private $PDO;
	function __construct()
	{
		//May load Engine Classes
		global $PDO;
		$this->PDO = $PDO;
	}
	
	function formulaireInscription()
	{
		$GLOBALS['CODEMENU'] = 4;
		include(HEADER);
		include(V."formulaireInscription.php");
		include(FOOTER);
	}
	function inscriptionUser()
	{
		global $ERRORS;
		require M."inscriptionUser.php";
		$userManager = new UserGRUD();
		try {
			if( isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password1']) && !empty($_POST['password1']) && isset($_POST['password2']) && !empty($_POST['password2'])){
				
				if( strlen($_POST['pseudo']) > TAILLE_PSEUDO )
					throw new Exception("Le nombre de caractère du pseudo ne peut pas excéder 20 caractères", 1);
				if( strlen($_POST['firstname']) > TAILLE_FIRSTNAME )
					throw new Exception("Le nombre de caractère du prénom ne peut pas excéder 20 caractères", 1);
				if( strlen($_POST['lastname']) > TAILLE_LASTNAME )
					throw new Exception("Le nombre de caractère du nom ne peut pas excéder 20 caractères", 1);

				$pseudo = $_POST['pseudo'];
				$password1 = $_POST['password1'];
				$password2 = $_POST['password2'];
				$firstname = $_POST['firstname'];
				$lastname = $_POST['lastname'];
				$email = $_POST['email'];
				
				if($password1!=$password2)
					throw new Exception("Les mots de passe ne sont pas identiques", 1);

				if(strlen($password2) <= 5)
					$ERRORS[]=new Error("Le mot de passe est court. A vos risques et périls !","warning");

				$userManager->inscrireUser($pseudo,$password1,$firstname,$lastname,$email );

				$ERRORS[] = new Error("Vous êtes inscrit ! ","success");

			}else
				throw new Exception("Il manque des informations pour vous inscrire.", 1);

		} catch (Exception $e) {
			$ERRORS[]=new Error($e->getMessage());
		}
		//On ré affiche le formulaire
		$this->formulaireInscription();
	}
}