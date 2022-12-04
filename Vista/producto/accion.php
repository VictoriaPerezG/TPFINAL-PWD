<?php
include_once '../../configuracion.php';
$datos = data_submitted();
$resp = false;
$objTrans = new AbmProducto();

if($datos['accion']=="nuevo"){
    $arrayProducto = ["pronombre" => $datos['pronombre']]; //Creo un array asociativo 
    $producto = $objTrans->buscar($arrayProducto);

    if(count($producto)==1){
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'Producto ya existente', 'salida' => '1'));
    }
    else{
        $resp = $objTrans->abm($datos);
        if($resp){
            if ($_FILES['archivo']['error'] <= 0){
                $direccion = '../img/';
                //mkdir($direccion, 0777);
                
                //Obtiene la extension del archivo
                $extension = pathinfo($direccion.$_FILES['archivo']['name'], PATHINFO_EXTENSION);
                
                //Copia la foto adjuntada en la carpeta img
                copy($_FILES['archivo']['tmp_name'], $direccion.$_FILES['archivo']['name']);
                
                //Cambia el nombre de la imagen al nombre del producto
                rename($direccion.$_FILES['archivo']['name'], $direccion.$datos['pronombre'].".".$extension);

                echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Producto insertado', 'salida' => '0'));
            }
            else{
                echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Producto insertado sin imagen', 'salida' => '0'));
            }
            
        }else {
            echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No pudo concretarse la insercion', 'salida' => '1'));
        }
    }
}

if($datos['accion']=="editar"){
    //print_r($datos);
    $resp = $objTrans->abm($datos);
    if($resp){
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Producto actualizado', 'salida' => '0'));
    }else {
        echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No pudo concretarse la actualizacion', 'salida' => '1'));
    }
}



if($datos['accion']=="cancelar"){
    $idcompra = $datos['idcompra'];

    //Elimina las comprasitems y actualiza el stock, Actualiza la compraestado y crea una nueva
    $salida = cancelarCompraCarrito2($idcompra);
    if($salida){
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Compra cancelada", 'salida' => '0'));
    }else{
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "No se pudo cancelar la compra", 'salida' => '1'));
    }
}



if($datos['accion']=="aceptar"){
    $idcompra = $datos['idcompra'];

    //Actualiza la compraestado (la fechafin)
    $obj = ultimaCompraEstado2($idcompra); //Obtengo compraEstado
    $fechaActual = date('Y-m-d H:i:s', time()); //Fecha actual
    $obj->setCeFechaFin($fechaActual);
    $obj->modificar();

    //Doy de alta una nueva compraestado
    $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 3, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
    $objCompraEstado = new AbmCompraEstado();
    $salida = $objCompraEstado->alta($arrayAsociativo);

    if($salida){
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Compra aceptada", 'salida' => '0'));
    }else{
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "No se pudo aceptar la compra", 'salida' => '1'));
    }
}


if($datos['accion']=="enviar"){
    $idcompra = $datos['idcompra'];

    //Actualiza la compraestado (la fechafin)
    $obj = ultimaCompraEstado2($idcompra); //Obtengo compraEstado
    $fechaActual = date('Y-m-d H:i:s', time()); //Fecha actual
    $obj->setCeFechaFin($fechaActual);
    $obj->modificar();

    //Doy de alta una nueva compraestado
    $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 4, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
    $objCompraEstado = new AbmCompraEstado();
    $salida = $objCompraEstado->alta($arrayAsociativo);

    if($salida){
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Compra enviada", 'salida' => '0'));
    }else{
        echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "No se pudo aceptar la compra", 'salida' => '1'));
    }
}



//Se invoca cuando se selecciona cancelar la compra desde el carrito
function cancelarCompraCarrito2($idcompra){
    eliminarCompras2($idcompra); //Elimina las comprasitems y actualiza el stock
    
    //Actualiza la compraestado (la fechafin)
    $obj = ultimaCompraEstado2($idcompra);
    $fechaActual = date('Y-m-d H:i:s', time()); //Fecha actual
    $obj->setCeFechaFin($fechaActual);
    $obj->modificar();

    //Doy de alta una nueva compraestado
    $arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 5, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
    $objCompraEstado = new AbmCompraEstado();
    $salida = $objCompraEstado->alta($arrayAsociativo);

    return $salida;
}

//Obtengo la ultima compraestado de la compra con idcompra
function ultimaCompraEstado2($idcompra){
    $arrayAsociativo = ["idcompra" => $idcompra];
    $objCompraEstado = new AbmCompraEstado();
    $array = $objCompraEstado->buscar($arrayAsociativo);
    return end($array);
}

//Elimina las comprasitems y actualiza el stock
function eliminarCompras2($idcompra){
    $compras = comprasItems2($idcompra);
    foreach($compras as $objCompraItem){
        actualizarStockEliminado2($objCompraItem->getObjproducto()->getIdproducto(), $objCompraItem->getCantidad());
        $arrayAsociativo = ["idcompraitem" => $objCompraItem->getIdcompraitem(), "accion" => "borrar"];
        $objCompraItem = new AbmCompraitem();
        $objCompraItem->abm($arrayAsociativo);
    }
}

//Obtengo todas las compras items de la compra idcompra
function comprasItems2($idCompra){
    $arreglo = array();
    $objCompraItem = new AbmCompraitem();
    $arrayAsociativo = ["idcompra" => $idCompra];
    $arreglo = $objCompraItem->buscar($arrayAsociativo);
    return $arreglo;
}

function actualizarStockEliminado2($idproducto, $cantidadComprada){
    $res=false;
    $objAbmProducto = new AbmProducto();
    $objProducto = $objAbmProducto->buscar(["idproducto" => $idproducto]);
    $nuevoStock = $objProducto[0]->getProcantstock()+$cantidadComprada;
    
    $param = ["idproducto" => $idproducto, "pronombre" => $objProducto[0]->getPronombre(), "prodetalle" => $objProducto[0]->getProdetalle(), "procantstock" => $nuevoStock, "proestado" => $objProducto[0]->getProestado(), "proprecio" => $objProducto[0]->getProprecio()];
    
    if($objAbmProducto->modificacion($param)){
        $res=true;
    }
    return $res;
}


?>