<?php 
date_default_timezone_set("America/Lima");

function fechaC(){

$mes = array("enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre");
$dia = array("domingo","lunes","martes","miercoles","jueves","viernes","sabado");
return  "Perú, " . $dia[date("w")] . " " . date("d") . " de " . $mes[date("m")-1] . " de " . date("Y") . "<br>";	
	

	}


 ?>