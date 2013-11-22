<?php

/**
* Class Accueil
*/
class Accueil
{
	
	function __construct()
	{
	}

	function presentation()
	{
		$GLOBALS['CODEMENU'] = 1;
		include(HEADER);
		include(V."presentation.php");
		include(FOOTER);
	}
}
