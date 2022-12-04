<?php
include_once '../../configuracion.php';
$objSession = new Session();
$sesionCerrada = $objSession->cerrar();

if($sesionCerrada){
    echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Sesion finalizada', 'salida' => '0'));
}else{
    echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Error al cerrar sesion', 'salida' => '1'));
}
?>