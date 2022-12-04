<?php
include_once '../../configuracion.php';
$datos = data_submitted();
//print_r($datos);

$resp = false;
$objTrans = new AbmUsuario();

if($datos['accion']=="editarPerfil"){
    $resp = $objTrans->abm($datos);
    if($resp){
    	echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Datos actualizados', 'salida' => '0'));
    }else{
    	echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo actualizar', 'salida' => '1'));
    }
}


//Accion invocada cuando se selecciona "Comprar" en el producto
if($datos['accion']=="nuevo"){
	$idusuario = $datos['idusuario'];

	if(esCarritoActivo($idusuario)){ //Si tiene una compra pendiente
		$idcompra = ultimaCompraCarrito($idusuario);
		$altaCompraItem = cargarCompraItem($datos, $idcompra); //cargo la compraitem
		if(!$altaCompraItem){
			echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar item de la compra', 'salida' => '1'));
		}else{
			$actStock = actualizarStock($datos['idproducto'], $datos['cantidad']);
			if($actStock){
				echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Agregado al carrito', 'salida' => '0'));
			}
			else{
				echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo actualizar stock', 'salida' => '1'));
			}
			
		}	    
	}
	else{//Es la 1 compra del carrito - Creo la compra, compraitem y compraEstado
	    //Cargo la compra
		$objAbmCompra = new AbmCompra();
		$altaCompra = $objAbmCompra->alta($datos);
		if($altaCompra){ //se dio de alta la compra
			$listaCompras = $objAbmCompra->buscar(null);
			$ultimaCompra = (end($listaCompras));
			$idUltimaCompra = $ultimaCompra->getIdcompra();

			//Cargo compraitem
			$objCompraItem = new AbmCompraitem();
			$arrayAsociativoCompraItem = ["idproducto" => $datos['idproducto'], "idcompra" => $idUltimaCompra, "cicantidad" => $datos['cantidad']];
			$altaCompraItem = $objCompraItem->alta($arrayAsociativoCompraItem);
			
			if(!$altaCompraItem){
				echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar item de la compra', 'salida' => '1'));
			}
			else{//Cargo compraEstado

				$actStock = actualizarStock($datos['idproducto'], $datos['cantidad']); //Actualizo stock

				if($actStock){
					$objCompraEstado = new AbmCompraEstado();
					$fechaInicio = date('Y-m-d H:i:s', time()); //Fecha actual
					$arrayAsociativoCompra = ["idcompra" => $idUltimaCompra,"idcompraestadotipo" => 1,"cefechaini" => $fechaInicio,"cefechafin" => '0000-00-00 00:00:00'];
		    		$altaCompraEstado = $objCompraEstado->alta($arrayAsociativoCompra);
		    		if(!$altaCompraEstado){
		    			echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar estado de la compra', 'salida' => '1'));
		    		}
		    		else{
		    			echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => 'Agregado al carrito', 'salida' => '0'));
		    		}
		    	}
		    	else{
		    		echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo actualizar stock', 'salida' => '1'));
		    	}
			}
		}
		else{
			echo json_encode(array('mensaje1' => 'Error', 'mensaje2' => 'No se pudo cargar la compra', 'salida' => '1'));
		}
	}
}


//Hay cambio de estado
//Elimina el producto del carrito
if($datos['accion']=="eliminar"){

	$id = $datos['valor'];
	$idcompraitem = $datos['idcompraitem'];
	$idcompra = $datos['idcompra'];
	$cantidad = $datos['cantidad'];

	//Elimino compraitem-actualizo compraestado-inserto compraestado
	if(esUltimaCompraItems($datos['idcompra'])){
		cancelarCompraCarrito($datos['idcompra']);
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Producto eliminado", 'salida' => '0'));
	}
	else{
		//Solo elimino compraitem
		$objCompraItem = new AbmCompraitem();
		$objCompraItem = $objCompraItem->buscar(["idcompraitem" => $idcompraitem]);
		actualizarStockEliminado($objCompraItem[0]->getObjproducto()->getIdproducto(), $objCompraItem[0]->getCantidad());

		$objCompraItem2 = new AbmCompraitem();
		$arrayCompraItem = ["idcompraitem" => $idcompraitem, "accion" => "borrar"];
		$bajaCompraItem = $objCompraItem2->abm($arrayCompraItem);
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Producto eliminado", 'salida' => '0'));
	}
}

//Cancela la compra del carrito
if($datos['accion']=="cancelar"){
	$idcompra = $datos['idcompra'];

	//Elimina las comprasitems y actualiza el stock, Actualiza la compraestado y crea una nueva
	$salida = cancelarCompraCarrito($idcompra);
	if($salida){
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Compra cancelada", 'salida' => '0'));
	}else{
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "No se pudo cancelar la compra", 'salida' => '1'));
	}
}



//Ahi cambio de estado - La compra pasa de estado borrador a estado iniciada
//Cuando desde Mi carrito selecciona Comprar
if($datos['accion']=="comprar"){
	$idcompra = $datos['idcompra'];

	//Actualiza la compraestado (la fechafin)
	$obj = ultimaCompraEstado($idcompra); //Obtengo compraEstado
	$fechaActual = date('Y-m-d H:i:s', time()); //Fecha actual
	$obj[0]->setCeFechaFin($fechaActual);
	$obj[0]->modificar();

	//Doy de alta una nueva compraestado
	$arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 2, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
    $objCompraEstado = new AbmCompraEstado();
  	$salida = $objCompraEstado->alta($arrayAsociativo);

	if($salida){
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "Compra realizada", 'salida' => '0'));
	}else{
		echo json_encode(array('mensaje1' => 'Exito', 'mensaje2' => "No se pudo realizar la compra", 'salida' => '1'));
	}
}




function esCarritoActivo($idusuario){
	$respuesta = false;
	$objAbmCompra = new AbmCompra();
	$ultimaCompraUser = $objAbmCompra->ultimaCompra($idusuario); //Obtengo la ultima compra del user idusuario
	if ($ultimaCompraUser != null) { //Si existe, verifico si es en estado BORRADOR
		$idUltimaCompra = $ultimaCompraUser->getIdcompra();

	    $estadoActual = estadoActualCompra($idUltimaCompra);
	    if($estadoActual=="1"){ //Esta en estado borrador
	    	$respuesta=true;
	    }
	}
	return $respuesta;
}


function cargarCompraItem($datos, $idUltimaCompra){
	$respuesta=true;
	$objCompraItem = new AbmCompraitem();
	$arrayAsociativoCompraItem = ["idproducto" => $datos['idproducto'],"idcompra" => $idUltimaCompra,"cicantidad" => $datos['cantidad']];
	$altaCompraItem = $objCompraItem->alta($arrayAsociativoCompraItem);
	if(!$altaCompraItem){
		$respuesta = false;
	}
	return $respuesta;
}


function actualizarStock($idproducto, $cantidadComprada){
	$res=false;
	$objAbmProducto = new AbmProducto();
	$objProducto = $objAbmProducto->buscar(["idproducto" => $idproducto]);
	$nuevoStock = $objProducto[0]->getProcantstock()-$cantidadComprada;
	
	$param = ["idproducto" => $idproducto, "pronombre" => $objProducto[0]->getPronombre(), "prodetalle" => $objProducto[0]->getProdetalle(), "procantstock" => $nuevoStock, "proestado" => $objProducto[0]->getProestado(), "proprecio" => $objProducto[0]->getProprecio()];
	
	if($objAbmProducto->modificacion($param)){
		$res=true;
	}
	return $res;
}


function actualizarStockEliminado($idproducto, $cantidadComprada){
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


//Obtengo el id de compra en estado borrador
function ultimaCompraCarrito($idusuario){
	$respuesta = 0;
	$objAbmCompra = new AbmCompra();
	$ultimaCompraUser = $objAbmCompra->ultimaCompra($idusuario); //Obtengo la ultima compra del user idusuario
	if ($ultimaCompraUser != null) { //Si existe, verifico si es en estado BORRADOR
		$idUltimaCompra = $ultimaCompraUser->getIdcompra();
		$objAbmCompraEstado = new AbmCompraEstado();
		$arrayAsociativoCompra = ["idcompra" => $idUltimaCompra, "idcompraestadotipo" => 1,"cefechafin" => '0000-00-00 00:00:00'];
	    $arreglo = $objAbmCompraEstado->buscar($arrayAsociativoCompra);
	    if($arreglo!= null){ //Existe compra en estado borrador
	    	$respuesta = $idUltimaCompra;
	    }
	}
	return $respuesta;
}

//Obtengo todas las compras items de la compra idcompra
function comprasItems($idCompra){
	$arreglo = array();
	$objCompraItem = new AbmCompraitem();
	$arrayAsociativo = ["idcompra" => $idCompra];
	$arreglo = $objCompraItem->buscar($arrayAsociativo);
	return $arreglo;
}

//Obtengo la ultima compraestado de la compra con idcompra
function ultimaCompraEstado($idcompra){
    //$arrayAsociativo = ["idcompra" => $idcompra];
    $arrayAsociativo = ["idcompra" => $idcompra, "cefechafin" => "0000-00-00 00:00:00"];
    $objCompraEstado = new AbmCompraEstado();
    $array = $objCompraEstado->buscar($arrayAsociativo);
    //return end($array);
    return $array;
}


//Se invoca cuando se selecciona cancelar la compra desde el carrito
function cancelarCompraCarrito($idcompra){
	eliminarCompras($idcompra); //Elimina las comprasitems y actualiza el stock
	
	//Actualiza la compraestado (la fechafin)
	$obj = ultimaCompraEstado($idcompra);
	$fechaActual = date('Y-m-d H:i:s', time()); //Fecha actual
	$obj->setCeFechaFin($fechaActual);
	$obj->modificar();

	//Doy de alta una nueva compraestado
	$arrayAsociativo = ["idcompra" => $idcompra, "idcompraestadotipo" => 5, "cefechaini" => $fechaActual, "cefechafin" => '0000-00-00 00:00:00'];
    $objCompraEstado = new AbmCompraEstado();
  	$salida = $objCompraEstado->alta($arrayAsociativo);

  	return $salida;
}

//Elimina las comprasitems y actualiza el stock
function eliminarCompras($idcompra){
	$compras = comprasItems($idcompra);
	foreach($compras as $objCompraItem){
		actualizarStockEliminado($objCompraItem->getObjproducto()->getIdproducto(), $objCompraItem->getCantidad());
		$arrayAsociativo = ["idcompraitem" => $objCompraItem->getIdcompraitem(), "accion" => "borrar"];
		$objCompraItem = new AbmCompraitem();
		$objCompraItem->abm($arrayAsociativo);
	}
}


//Obtengo el estado actual de la compra con idcompra
function estadoActualCompra($idcompra){
    //$arrayAsociativo = ["idcompra" => $idcompra];
    $arrayAsociativo = ["idcompra" => $idcompra, "cefechafin" => "0000-00-00 00:00:00"];
    $objCompraEstado = new AbmCompraEstado();
    $array = $objCompraEstado->buscar($arrayAsociativo);
    //$objCompraEstado=end($array);
    //$estado = $objCompraEstado->getCompraEstadoTipo()->getIdCompraEstadoTipo();
    $estado = $array[0]->getCompraEstadoTipo()->getIdCompraEstadoTipo();
    return $estado;
}

function esUltimaCompraItems($idCompra){
  $res=false;
  $arreglo = array();
  $objCompraItem = new AbmCompraitem();
  $arrayAsociativo = ["idcompra" => $idCompra];
  $arreglo = $objCompraItem->buscar($arrayAsociativo);
  //print($arreglo);

  $ultimo = count($arreglo);
  if($ultimo==1){
  	$res=true;
  }
  return $res;
}

?>