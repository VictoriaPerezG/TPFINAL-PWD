<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";
include_once "../funciones/funciones.php";

if($resp){
  $idusuario = $_SESSION['idusuario']; //Obtengo el id del usuario


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <title>Mi carrito</title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../dataTables.bootstrap5.min.css">
  <link rel="stylesheet" type="text/css" href="../css/micarrito.css">
  
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  </head>

<body class="fondo"><br><br>
  
  <div class="container">
    <?php 
  //OBTENGO TODAS LAS COMPRAS DEL USUARIO EN SESION

  if(esBorrador($idusuario)){ //Tiene carrito activo
     $objAbmCompra = new AbmCompra();
     $idCompra = $objAbmCompra->idUltimaCompra($idusuario);
     $comprasItems = (comprasItems($idCompra));
    ?>

    <div class="container" align="center"><span class="titulo2">MI CARRITO</span></div><br>
      <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID</th>
          <th id="columna">NOMBRE</th>
          <th id="columna">PRECIO</th>
          <th id="columna">CANTIDAD</th>
          <th id="columna">TOTAL</th>
          <th id="columna" style="text-align: center;">ACCIONES</th>
        </tr></thead>
        <tbody>

    <?php
      $total = 0;
      foreach($comprasItems as $objAbmProducto){ 
        $precio = $objAbmProducto->getCantidad()*$objAbmProducto->getObjproducto()->getProprecio();
        $total = $total + $precio;

        $salida = "ID: ".$objAbmProducto->getObjproducto()->getIdproducto(). '\n'." NOMBRE: ".$objAbmProducto->getObjproducto()->getPronombre(). '\n'."STOCK: ".$objAbmProducto->getObjproducto()->getProcantstock(). '\n'."PRECIO: ".$objAbmProducto->getObjproducto()->getProprecio(). '\n'."DETALLES".'\n'.$objAbmProducto->getObjproducto()->getProdetalle(). '\n';

        $imagenGuardada = "../img/".$objAbmProducto->getObjproducto()->getPronombre().".jpg";

        ?>
          <tr>
            <td id="fila"><?php echo $objAbmProducto->getObjproducto()->getIdproducto();?></td>
            <td id="fila"><?php echo $objAbmProducto->getObjproducto()->getPronombre();?></td>
            <td id="fila"><?php echo $objAbmProducto->getObjproducto()->getProprecio();?></td>
            <td id="fila"><?php echo $objAbmProducto->getCantidad();?></td>
            <td id="fila"><?php echo $precio;?></td>
            <td id="fila" style="text-align: center;">
              <button id="botonDetalles" title="Detalles" onclick="verDatos('<?php echo $salida ?>', '<?php echo $imagenGuardada ?>')">Detalles</button>
              <button id="botonEliminar" title="Eliminar" onclick="eliminar('<?php echo $objAbmProducto->getObjproducto()->getIdproducto();?>', '<?php echo "eliminar"?>', '<?php echo $objAbmProducto->getIdcompraitem();?>', '<?php echo $objAbmProducto->getObjcompra()->getIdcompra();?>', '<?php echo $objAbmProducto->getCantidad();?>')">Eliminar</button>
            </td>
          </tr>
    <?php    
      }
    ?>
    <tr><td colspan="4">TOTAL: </td><td colspan="2"><?php echo $total;?></td></tr>
    </tbody>
    </table>

  <button id="botonComprar" onclick="comprar('<?php echo $idCompra?>', '<?php echo "comprar"?>')">Comprar</button>
  <?php if($total != 0){ ?>
    <button id="botonCancelar" onclick="cancelar('<?php echo $objAbmProducto->getObjcompra()->getIdcompra();?>', '<?php echo "cancelar" ?>')">Cancelar</button>
  <?php }

  }
  else{ ?>
      <div style="text-align: center;color:#3498DB;font-size: 25px;"><?php echo "CARRITO VACIO" ?></div>
  <?php } ?>


  </div><br><br>
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js"></script>
  <script type="text/javascript" src="../jquery-3.5.1.js"></script>
  <script type="text/javascript" src="../jquery.dataTables.min.js"></script>
  <script type="text/javascript"src="../dataTables.bootstrap5.min.js"></script> 
  </body>
</html>

<?php
}
else{  ?>
    <br>
    <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php echo "No tiene permisos";?></span></div>
    <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php echo "Sera redirigido";?></span></div>
    <meta http-equiv="refresh" content="2;url=../principal/home.php"/>
  <?php
}
?>


<script type="text/javascript">
  function verDatos($datos, $foto){
    swal.fire({
      title: '<span style="color:black; font-size: 16px; font-family: Arial;">'+$datos+'</span>', 
      width:'650px',
      imageUrl: $foto,
      imageWidth: 300,
      imageHeight: 150,
      allowOutsideClick: false,
      confirmButtonColor: '#3498DB',
    });
  }

function eliminar(valor, accion, idcompraitem, idcompra, cantidad){
  var datos = {"valor":valor, "accion":accion, "idcompraitem":idcompraitem, "idcompra":idcompra, "cantidad":cantidad};
  Swal.fire({
    title: '¿Desea eliminar el producto?',
    width:'600px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3498DB',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
  }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: 'accion.php',
          type:'POST',
          data: datos,
          success: function(data){
            var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
              }
              else{
                return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
              }
            }
        });
        return false;
      }
    });
}


function cancelar(idcompra, accion){
  var datos = {"idcompra":idcompra, "accion":accion};
  Swal.fire({
    title: '¿Desea cancelar la compra?',
    width:'600px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3498DB',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
  }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: 'accion.php',
          type:'POST',
          data: datos,
          success: function(data){
            var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExito(jsonData.mensaje1, jsonData.mensaje2);
              }
              else{
                return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
              }
            }
        });
        return false;
      }
    });
}


function comprar(idcompra, accion){
  var datos = {"idcompra":idcompra, "accion":accion};
  Swal.fire({
    title: '¿Desea realizar la compra?',
    width:'600px',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#3498DB',
    confirmButtonText: 'Aceptar',
    cancelButtonColor: 'red',
    allowOutsideClick: false,
  }).then((willDelete) => {
      if (willDelete) {
        $.ajax({
          url: 'accion.php',
          type:'POST',
          data: datos,
          success: function(data){
            var jsonData = JSON.parse(data);
              if(jsonData.salida == 0){
                return mensajeExitoCompra(jsonData.mensaje1, jsonData.mensaje2);
              }
              else{
                return mensajeError(jsonData.mensaje1, jsonData.mensaje2);
              }
            }
        });
        return false;
      }
    });
}

  function mensajeError($mensaje1, $mensaje2){
    swal.fire({
      type: 'error',
      title: $mensaje1,
      text: $mensaje2,
      allowOutsideClick: false,
      width:'600px',
      imageWidth: '160px',
    });
    return false;
  }
/*
  function mensajeExito($mensaje1, $mensaje2){
    Swal.fire({
      type: 'success',
      title: $mensaje1,
      text: $mensaje2,
      confirmButtonColor: '#3498DB', 
      width:'600px',
      allowOutsideClick: false,
    }).then(function(){window.location.replace("micarrito.php");});
  }

   function mensajeExitoCompra($mensaje1, $mensaje2){
    Swal.fire({
      type: 'success',
      title: $mensaje1,
      text: $mensaje2,
      confirmButtonColor: '#3498DB', 
      width:'600px',
      allowOutsideClick: false,
    }).then(function(){window.location.replace("index.php");});
  }*/
</script>
