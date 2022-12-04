<?php
include_once("../estructura/cabecera.php");
include_once "../../configuracion.php";

if($resp){

//Verifica si tiene acceso este rol
  $link = $_SERVER['REQUEST_URI'];
  $nuevoLink = substr_replace($link, "../", 0, 19);
  $tienePermiso = $objTrans->tieneAcceso($nuevoLink);
 
//Verifica si tiene acceso este rol
/*$menu = new AbmMenu();
$link = $_SERVER['REQUEST_URI'];
$nuevoLink = substr_replace($link, "../", 0, 19);
$tienePermiso = $menu->tieneAcceso($objMenus, $nuevoLink);*/

if($tienePermiso){


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <title>Listar productos</title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../dataTables.bootstrap5.min.css">
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="../css/miscompras.css">
  
</head>
<body class="fondo"><br><br>
  
  <div class="container">
    <div class="container" align="center"><span class="titulo2">MIS COMPRAS</span></div><br>
    
    <script type="text/javascript" src="../sweetalert/sweetalert2.min.js"></script>
    <script type="text/javascript" src="../jquery-3.5.1.js"></script>
    <script type="text/javascript" src="../jquery.dataTables.min.js"></script>
    <script type="text/javascript"src="../dataTables.bootstrap5.min.js"></script> 
  </div>
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
<?php
    }
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
      "columnDefs": [ { "orderable": false, "targets": 5 }, { "orderable": false, "targets": 4 }], //ocultar para columna 0
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
</script>
