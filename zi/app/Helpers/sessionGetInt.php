<?php 

	function sessionGetInt($varName)
	{    
		$x=session()->get($varName);
		
		settype( $x, "integer");	
		
		return $x;
	}
?>