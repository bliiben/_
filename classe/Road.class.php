<?php
/**
* Road
*/
class Road
{
	function __construct($module,$action)
	{
		// Create an instance of the module and call his action
		if(!file_exists(C.$module.".php"))
			throw new Exception("Module does not exist", 1);
		
		require C.$module.".php";
		$¤ = new $module();
		if(!method_exists($¤, $action))
			throw new Exception("Action of the module does not exist", 1);
			
		$¤->$action();
	}
}
