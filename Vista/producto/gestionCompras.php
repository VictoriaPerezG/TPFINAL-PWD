<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";
/*
if($resp){

$objAbm = new AbmCompraEstado();
$array = ["cefechafin" => "0000-00-00 00:00:00"];
$lista = $objAbm->buscar($array);

//Verifica si tiene acceso este rol
$menu = new AbmMenu();
$link = $_SERVER['REQUEST_URI'];
$nuevoLink = substr_replace($link, "../", 0, 19);
$tienePermiso = $menu->tieneAcceso($objMenus, $nuevoLink);

if($tienePermiso){*/

  if($resp){
    $objAbm = new AbmCompraEstado();
    $array = ["cefechafin" => "0000-00-00 00:00:00"];
    $lista = $objAbm->buscar($array);
    
    //Verifica si tiene acceso este rol
    $link = $_SERVER['REQUEST_URI'];
    $nuevoLink = substr_replace($link, "../", 0, 19);
    $tienePermiso = $objTrans->tieneAcceso($nuevoLink);
  
  if($tienePermiso){

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <title>Gestionar compras</title>
  <!--link rel="icon" type="image/png" href="../img/logo.ico"/-->
  <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../dataTables.bootstrap5.min.css">
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="../css/gestionCompras.css">
  
</head>
<body class="fondo"><br><br>
  
  <div class="container">
    <div class="container" align="center"><span class="titulo2">GESTIÓN DE COMPRAS</span></div><br>

    <table id="ejemplo" class="table table-striped table-bordered" style="width:100%">
        <thead><tr>
          <th id="columna">ID COMPRA</th>
          <th id="columna">ESTADO</th>
          <th id="columna">FECHA DE INICIO</th>
          <th id="columna">USUARIO</th>
          <th id="columna" style="text-align: center;">ACCIONES</th>
        </tr></thead>
        <tbody>
        <?php 
          if($lista!=null){
            foreach($lista as $objAbm){ 
                if($objAbm->getCompraEstadoTipo()->getCetDescripcion()!="cancelada"){
           ?>
              <tr>
                <td id="fila"><?php echo $objAbm->getCompra()->getIdcompra();?></td>
                <td id="fila"><?php echo $objAbm->getCompraEstadoTipo()->getCetDescripcion();?></td>
                <td id="fila"><?php echo $objAbm->getCeFechaIni();?></td>
                <td id="fila"><?php echo $objAbm->getCompra()->getObjusuario()->getUsnombre()?></td>
                <td id="fila" style="text-align: center;">

                <?php if($objAbm->getCompraEstadoTipo()->getCetDescripcion()=="iniciada"){?>
                  <button id="boton" onclick="aceptar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "aceptar" ?>')">Aceptar</button>
                  <button id="boton" onclick="cancelar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "cancelar" ?>')">Cancelar</button>
                <?php } ?>

                <?php if($objAbm->getCompraEstadoTipo()->getCetDescripcion()=="aceptada"){?>
                  <button id="boton" onclick="enviar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "enviar" ?>')">Enviar</button>
                  <button id="boton" onclick="cancelar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "cancelar" ?>')">Cancelar</button>
                <?php } ?>

                <?php if($objAbm->getCompraEstadoTipo()->getCetDescripcion()=="enviada"){?>
                  <button id="boton" onclick="cancelar('<?php echo $objAbm->getCompra()->getIdcompra(); ?>', '<?php echo "cancelar" ?>')">Cancelar</button>
                <?php } ?>

                </td>
              </tr>
        <?php   } 
            } 
            }       
          ?>
        </tbody>
    </table>
  </div><br><br>
    
    <script type="text/javascript" src="../sweetalert/sweetalert2.min.js"></script>
    <script type="text/javascript" src="../jquery-3.5.1.js"></script>
    <script type="text/javascript" src="../jquery.dataTables.min.js"></script>
    <script type="text/javascript"src="../dataTables.bootstrap5.min.js"></script> 
  </body>
</html>
<?php
  }
  else{ ?>
      <br>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php echo "No tiene permisos";?></span></div>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php echo "Sera redirigido";?></span></div>
      <meta http-equiv="refresh" content="2;url=../principal/home.php" />
<?php  }
}
else{ ?>
      <br>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php echo "No tiene permisos";?></span></div>
      <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php echo "Sera redirigido";?></span></div>
      <meta http-equiv="refresh" content="2;url=../principal/home.php" />
<?php    }
?>



<script>
  $(document).ready(function () {
    $('#ejemplo').DataTable({
      "language": {
          "lengthMenu": "Mostrar _MENU_ registros",
          "zeroRecords": "No se encontraron resultados",
          "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
          "infoFiltered": "(filtrado de un total de _MAX_ registros)",
          "sSearch": "Buscar:",
          "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
          },
          "sProcessing":"Procesando...",
      },
      "columnDefs": [ { "orderable": false, "targets": 4 }], //ocultar para columna 0
    });

  /*  $('#botonAltaProducto').on('click', function() {
      window.location='nuevoProducto.php';
      return false;
    });*/

  });

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
  }).then((result) => {
      if (result.value){
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

function aceptar(idcompra, accion){
  var datos = {"idcompra":idcompra, "accion":accion};
  Swal.fire({
    title: '¿Desea aceptar la compra?',
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

function enviar(idcompra, accion){
  var datos = {"idcompra":idcompra, "accion":accion};
  Swal.fire({
    title: '¿Desea enviar la compra?',
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

/*
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

  function mensajeExito($mensaje1, $mensaje2){
    Swal.fire({
      type: 'success',
      title: $mensaje1,
      text: $mensaje2,
      confirmButtonColor: '#3498DB', 
      width:'600px',
      allowOutsideClick: false,
    }).then(function(){window.location.replace("gestionCompras.php");});
  }*/

</script>

