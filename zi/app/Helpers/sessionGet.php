<?php 

	function sessionGet($varName)
	{    
		$x=session()->get($varName);
				
		return $x;
	}
?>