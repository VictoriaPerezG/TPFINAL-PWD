<?php
include_once("../estructura/cabecera.php");
include_once '../../configuracion.php';

if($resp){
  $idUsuario = $_SESSION['idusuario']; //Obtengo el id del usuario
  $datos = data_submitted();

  $objC = new AbmProducto();
  $obj = NULL;
  if (isset($datos['idproducto']) && $datos['idproducto'] <> -1){
      $listaTabla = $objC->buscar($datos);
      if (count($listaTabla)==1){
          $obj= $listaTabla[0];
      }
  } 


?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title><?php echo "Compra de producto"?></title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
  <script type="text/javascript" src="../jquery-3.5.1.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <link rel="stylesheet" type="text/css" href="../css/compra.css">
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js" ></script>
</head>

<body class="fondo"><br><br>
<div class="container" align="center">

    <span class="titulo2"><?php echo "COMPRA DE PRODUCTO"?></span><br><br>

      <?php $imagenGuardada = "../img/".$obj->getPronombre().".jpg";?>
        <img id="imagenBorde" src="<?php echo $imagenGuardada ?>" width="450" height="280"><br><br>
       
        <div><span>Nombre: </span><span><?php echo $obj->getPronombre(); ?></span></div>

        <label class="subtitulo">Detalles</label><br/>
          <?php echo $obj->getProdetalle()?><br>

        <div><span>Stock: </span><span><?php echo $obj->getProcantstock()?></span></div>

        <div><span>Precio: </span><span><?php echo $obj->getProprecio() ?></span></div>

        <div><span>Estado: </span>
        <?php 
              if($obj->getProestado() == "1"){ ?>
                  <span>Habilitado</span></div>
        <?php }
              else{ ?>
                  <span>Inhabilitado</span></form>
        <?php }
        ?>


    <form method="post" id="formularioProducto" enctype="multipart/form-data"><br><br>

      <input id="idproducto" name ="idproducto" type="hidden" value="<?php echo ($obj !=null) ? $obj->getIdproducto() : "-1"?>" readonly>

      <input id="accion" name ="accion" value="<?php echo "nuevo" ?>" type="hidden">
      <input id="precio" name ="precio" value="<?php echo $obj->getProprecio(); ?>" type="hidden">
      <input id="idusuario" name ="idusuario" value="<?php echo $idUsuario ?>" type="hidden">

      <input id="procantstock" type="hidden" name="procantstock" value="<?php echo $obj->getProcantstock()?>" readonly>

       <label class="subtitulo">Cantidad *</label><br/>
        <div class="input-group mx-auto">
          <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" style="text-transform: uppercase; border-color: #3498DB">
        </div>
        <div class="form-group col-md-12">
          <button class="btn btn-responsive float-right" type="submit" id="botonInsertar" title="Insertar">Agregar al carrito</button><br>
          <a href="index.php">Volver</a>
        </div>

    </form><br><br>

</div>
</div>
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
$('#botonInsertar').click(function(evento){
    pregunta = '¿Desea comprar el Producto?';
    evento.preventDefault();
    if(validarFormulario()){
        Swal.fire({
            title: pregunta,
            width:'600px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3498DB',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
        }).then((willDelete) => {
            if (willDelete) {
                var datos = new FormData($('#formularioProducto')[0]);
                $.ajax({
                    //url: 'cargarProducto.php',
                    url: 'accion.php',
                    type:'POST',
                    data: datos,
                    processData: false,
                    contentType: false,
                    success: function(data){
                        //console.log(data);
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
});


function validarFormulario(){
      console.log($("#cantidad").val() );

    if($("#cantidad").val() == ""){
      return mensajeError("Error", "Debe ingresar cantidad");
      return false;
    }
    if($("#cantidad").val() > $("#procantstock").val()){
      return mensajeError("Error", "Sin stock suficiente");
      return false;
    }

    return true;
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
    }).then(function(){window.location.replace("index.php");});
  }*/
 
</script>

