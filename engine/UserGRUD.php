<?php

/**
* GRUD user
*/
class UserGRUD implements Test
{
	private $PDO;
	function __construct()
	{
		global $PDO;
		$this->PDO = $PDO;
	}
	public function testClass(){
		if( ! strlen(crypt("mdp","salt"))==13 ){
			echo "Warning crypt(mdp) != 13";
		}
		print_r(self::generateSalt());
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
	//Crypt something
	public static function cryptSomething($something){
		$µ='';
		foreach (str_split($something,8) as $partOfSomething) {
			$salt = self::generateSalt();
			$µ .= crypt($partOfSomething,$salt);
		}
		return $µ;
	}
	public static function cryptIsEqualWith($something,$theCrypt){
		//Create chunk of 8 char with the password and 13 with the key
		$aMdp = str_split($something,8);
		$aKey = str_split($theCrypt,13);
		if( count($aKey) != count($aMdp) )
			return false;
		
		//Compare each chunck
		for ($i=0; $i < count($aMdp); $i++) { 
			if( crypt( $aMdp[$i] , $aKey[$i] ) != $aKey[$i] )
				return false;//If the chunck are not equals
		}
		return true;
	}
	//return a string of 10 caracter. completly random
	public static function generateSalt($len=10)
	{
		$str="";
		$characters="AZERTYUIOPQSDFGHJKLMWXCVBNazertyuiopqsdfghjklmwxcvbn1234567890";
		for ($i=0; $i < $len; $i++) { 
			$str.=$characters[rand(0,strlen($characters)-1)];
		}
		return $str;
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
	//Not used atm
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
		$st->execute(array('pseudo'=>$pseudo,'uuid'=>crypt($uuid)));

	}
	//Return true if user can connect by uuid
	public function connexionByUUID()
	{
		$_SESSION['connexionByUUID'] = true;
		if(isset($_COOKIE['uuid']) && isset($_COOKIE['pseudo'])){
			$st = $this->PDO->prepare("SELECT idUser,uuid FROM user WHERE pseudo=:pseudo ");
			$st->execute(array("pseudo"=>$_COOKIE['pseudo']));
			while($d=$st->fetch()){
				if( (substr(crypt($_COOKIE['uuid'],$d['uuid']),0,TAILLEBDUUID) == $d['uuid'] ) )
				{
					return $d['idUser'];
				}
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
