<?php 

	function priceVisible()
	{   
		$x= session()->get('price_hide');
		if ($x==='null' || $x==='') {
			$x=0;	
		};
		$y= $x;
		settype( $y, "integer");	

		return $y===0;
	}
	
?>