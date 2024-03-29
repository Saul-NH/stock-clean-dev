<?php 

   function getFormatPrice($number,$tipo_medida){

	 	if ( explode(".",$number)[1] > 0 ) {
	 		return '$ '.number_format($number,1).' x '. $tipo_medida;
	 	}else{
	 		return '$ '.number_format($number).' x '. $tipo_medida;
	 	}
 	}

 	function getFormatAmount($number,$tipo_medida){

	 	if ( explode(".",$number)[1] > 0 ) {
	 		return number_format($number,1).'  '. $tipo_medida.'s';
	 	}else{
	 		return number_format($number).'  '. $tipo_medida.'s';
	 	}
 	}

 	function getFormatSell($tipo_medida){
 		switch ($tipo_medida) {
 			case 'litro': return 'litros';
 			case 'kg': return 'kg';
 			case 'pieza': return 'piezas';
 			default:
 				return 'unidad';
 				break;
 		}
 	}