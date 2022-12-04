<?php 

function comprasItems($idCompra){
  $arreglo = array();
  $objCompraItem = new AbmCompraitem();
  $arrayAsociativo = ["idcompra" => $idCompra];
  $arreglo = $objCompraItem->buscar($arrayAsociativo);
  return $arreglo;
}  

//Obtengo el estado actual de la ultima compra con idcompra=$idcompra
function estadoActualCompra($idcompra){
    $arrayAsociativo = ["idcompra" => $idcompra];
    $objCompraEstado = new AbmCompraEstado();
    $array = $objCompraEstado->buscar($arrayAsociativo);
    $objCompraEstado=end($array);
    $estado = $objCompraEstado->getCompraEstadoTipo()->getIdCompraEstadoTipo();
    return $estado;
}

//Verifica si tiene compra en estado borrador
function esBorrador($idusuario){
  $res=false;
  $objCompra = new AbmCompra();
  $salida = $objCompra->ultimaCompra($idusuario);//Ultima compra del usr
  if($salida!=null){ //Si existe compra para el usr
    $estado = estadoActualCompra($salida->getIdcompra());
      if($estado == '1'){ //Si es borrador
        $res=true;
      }
  }
  return $res;
}

?>