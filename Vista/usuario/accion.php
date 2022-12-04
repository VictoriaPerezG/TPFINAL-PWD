<?php
include_once '../../configuracion.php';
$datos = data_submitted();
//print_r($datos);

$resp = false;
$objTrans = new AbmUsuario();

if($datos['accion']=="nuevo"){
    $arrayUsuario = ["usnombre" => $datos['usnombre']]; //Creo un array asociativo 
    $usuario = $objTrans->buscar($arrayUsuario);

    if(count($usuario)>=1){
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Nombre de usuario ya existe', 'salida' => '1'));
    }
    else{
        $resp = $objTrans->abm($datos);
        if($resp){
            echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Usuario insertado', 'salida' => '0'));
        }else {
            echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No pudo concretarse la insercion', 'salida' => '1'));
        }
    }
}

if($datos['accion']=="editar"){
    //print_r($datos);
    $resp = $objTrans->abm($datos);
    if($resp){
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Usuario actualizado', 'salida' => '0'));
    }else {
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No pudo concretarse la actualizacion', 'salida' => '1'));
    }
}


if($datos['accion']=="registrarse"){
    
    $arrayUsuario = ["usnombre" => $datos['usnombre']]; //Creo un array asociativo 
    $usuario = $objTrans->buscar($arrayUsuario);
    
    if(count($usuario)>=1){
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Nombre de usuario ya existe', 'salida' => '1'));
    }
    else{
        $resp = $objTrans->abm($datos);
        if($resp){
            echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Usuario registrado', 'salida' => '0'));
        }else {
            echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No pudo concretarse la insercion', 'salida' => '1'));
        }
    }
}
?>