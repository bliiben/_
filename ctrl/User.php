<?php
/**
* User class
*/
class User
{
	private $PDO,$userManager;
	function __construct()
	{
		//May load Engine Classes
		global $PDO;
		$this->PDO = $PDO;
		
		require M."UserGRUD.php";
		$this->userManager = new UserGRUD();
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

				$this->userManager->inscrireUser($pseudo,$password1,$firstname,$lastname,$email );

				$ERRORS[] = new Error("Vous êtes inscrit ! ","success");

			}else
				throw new Exception("Il manque des informations pour vous inscrire.", 1);

		} catch (Exception $e) {
			$ERRORS[]=new Error($e->getMessage());
		}
		//On ré affiche le formulaire
		$this->formulaireInscription();
	}
	function formulaireConnexion()
	{
		$GLOBALS['CODEMENU']=5;
		include(HEADER);
		include(V."formulaireConnexion.php");
		include(FOOTER);
	}
	function connexionUser()
	{
		global $ERRORS;
		if( isset($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['pseudo']) && !empty($_POST['password']) )
		{
			$result = $this->userManager->connexionUser($_POST['pseudo'],$_POST['password']);
			//Résult === idUser | false
			if($result!==false){
				$ERRORS[]=new Error("Connexion réussi","success");
				//Si l'user veut une connexion automatique
				if(isset($_POST['connect_auto']) && $_POST['connect_auto']=='on' )
				{
					
					//Save the uuid in the data base
					$this->userManager->registerUUIDToUser($_POST['pseudo']);
					//Save the session
					$this->saveUserInSession( $result );
				}
				//Redirection sur la page perso de l'user
				//ici ( Quand ça sera fait )
				$this->formulaireConnexion();//To remove

			}
			else{
				$ERRORS[]=new Error("La connexion est impossible. Pseudo ou mot de passe érronée");
				$this->formulaireConnexion();
			}
		}
		else
		{
			$ERRORS[]=new Error("Paramètres manquant pour la connexion");
			$this->formulaireConnexion();
		}
	}
	//Connect the user with the uuid
	public function connexionByUUID()
	{
		if(($idUser = $this->userManager->connexionByUUID()) !== false)
		{
			$this->saveUserInSession($idUser);
		}
	}
	
	private function saveUserInSession($idUser)
	{
		$this->userManager->saveUserInSession($idUser);
	}
}