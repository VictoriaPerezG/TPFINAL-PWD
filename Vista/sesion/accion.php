<?php
include_once '../../configuracion.php';
$datos = data_submitted();
//print_r($datos); va bien
$objUsuario = new AbmUsuario();
$arrayUsuario = ["usnombre" => strtoupper($datos['usnombre'])]; //Creo un array asociativo 
$usuario = $objUsuario->buscar($arrayUsuario);
//echo $usuario[0]->getIdusuario();
//echo $datos['uspass'];

if(count($usuario)> 0){ //existe el nombre de usuario ingresado
	if($datos['uspass'] == $usuario[0]->getUspass()){
		$objTrans = new Sesion(); //Crea la sesion y le pasa los valores 
        $resp = $objTrans->iniciar(strtoupper($datos['usnombre']),$datos['uspass']);
        if($resp) {
            echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Bienvenido al sistema', 'salida' => '0'));
        } else {
            echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Error, vuelva a intentarlo', 'salida' => '0'));
        }
	}
	else{
		echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Usuario y/o Clave incorrecto', 'salida' => '1'));
	}
}else{
	echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Usuario y/o Clave incorrecto', 'salida' => '1'));
}
?>
