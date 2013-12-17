<?php
/**
* Road
*/
class Road
{
	// Create an instance of the module and call his action
	function __construct($module,$action)
	{
		//Verify if the module exists in the website
		if(!file_exists(C.$module.".php"))
			throw new Exception("Module does not exist", 1);
		
		//Verify if we didn't called it before
		if(!class_exists($module)){
			require C.$module.".php";
		}

		//Create the module
		$¤ = new $module();

		//Verify if the method exists
		if(!method_exists($¤, $action))
			throw new Exception("Action of the module does not exist", 1);
		
		//Launch the code
		$¤->$action();
	}
}
