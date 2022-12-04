<?php
include_once("../estructura/cabecera.php");
include_once '../../configuracion.php';

if($resp ){
  $datos = data_submitted();
  $accion = $datos['accion']; //Obtengo el nombre de la accion (nuevo o editar)

  $objC = new AbmProducto();
  $obj = NULL;
  if (isset($datos['idproducto']) && $datos['idproducto'] <> -1){
      $listaTabla = $objC->buscar($datos);
      if (count($listaTabla)==1){
          $obj= $listaTabla[0];
      }
  }

  if($accion=="nuevo"){
    $titulo = "CARGAR PRODUCTO";
  }else{
    $titulo = "EDITAR PRODUCTO";
  }



  ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <title><?php echo $titulo?></title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../css/nuevoProducto.css">
  
  <script type="text/javascript" src="../jquery-3.5.1.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js" ></script>
</head>

<body class="fondo"><br><br>
<div class="container" align="center">

    <span class="titulo2"><?php echo $titulo?></span><br><br>

    <form method="post" id="formularioProducto" enctype="multipart/form-data">

      <input id="idproducto" name ="idproducto" type="hidden" value="<?php echo ($obj !=null) ? $obj->getIdproducto() : "-1"?>" readonly>
       
       <input id="accion" name ="accion" value="<?php echo ($datos['accion'] !=null) ? $datos['accion'] : "nose"?>" type="hidden">

        <label class="subtitulo">Nombre *</label><br/>
        <div class="input-group mx-auto">
          <input type="text" id="pronombre" name="pronombre" class="form-control" maxlength="15" style="text-transform: uppercase; border-color: #3498DB" value="<?php echo ($obj !=null) ? $obj->getPronombre() : ""?>">
        </div>


        <label class="subtitulo">Detalles *</label><br/>
        <div class="input-group mx-auto">
            <textarea type="text" id="prodetalle" name="prodetalle" class="form-control" autocomplete="off" maxlength="512" rows="4" style="text-transform: uppercase; resize:none; border-color: #3498DB"><?php echo ($obj !=null) ? $obj->getProdetalle() : "" ?></textarea>
        </div>


        <label class="subtitulo">Stock *</label><br/>
        <div class="input-group mx-auto">
            <input type="number" id="procantstock" name="procantstock" class="form-control" style="border-color: #3498DB" value="<?php echo ($obj !=null) ? $obj->getProcantstock() : ""?>">
        </div>


        <label class="subtitulo">Precio *</label><br/>
        <div class="input-group mx-auto">
            <input type="number" id="proprecio" name="proprecio" class="form-control" style="border-color: #3498DB" value="<?php echo ($obj !=null) ? $obj->getProprecio() : ""?>">
        </div>


        <label class="subtitulo">Estado</label><br/>
        <?php if($obj != null){
                if($obj->getProestado() == "1"){ ?>
                  <div class="input-group mx-auto">
                    <input type="radio" name="proestado" id="1" checked="checked" value="1" style="margin-right: 10px; margin-left: 10px;"><label>Habilitado</label>
      
                    <input type="radio" name="proestado" id="0" value="0" style="margin-right: 10px; margin-left: 10px;"><label>Inhabilitado</label>
                  </div>
        <?php    
                }else{?>
                    <div class="input-group mx-auto">
                      <input type="radio" name="proestado" id="1" value="1" style="margin-right: 10px; margin-left: 10px;"><label>Habilitado</label>
      
                      <input type="radio" name="proestado" id="0" checked="checked" value="0" style="margin-right: 10px; margin-left: 10px;"><label>Inhabilitado</label>
                    </div>
        <?php        }
              }else{ ?>
                  <div class="input-group mx-auto">
                    <input type="radio" name="proestado" id="1" checked="checked" value="1" style="margin-right: 10px; margin-left: 10px;"><label>Habilitado</label>
                    <input type="radio" name="proestado" id="0" value="0" style="margin-right: 10px; margin-left: 10px;"><label>Inhabilitado</label>
                  </div>
        <?php      }
        ?>
        <div>

      <?php if($obj != null){ 
              $mensajeImagen = "Reemplazar Imagen";
              $imagenGuardada = "../img/".$obj->getPronombre().".jpg";
      ?>
              <img id="imagenBorde" src="<?php echo $imagenGuardada ?>" width="400" height="250"><br><br>
      <?php
            }
            else{  $mensajeImagen = "Adjuntar Imagen";
            }
      ?>

        <label class="subtitulo"><?php echo $mensajeImagen;?></label><br/>
        <div class="form-group col-sm-12">
          <input name="archivo" id="archivo" type="file"><br>
        </div><br><br>

        <div class="form-group col-md-12">
          <div class="float-left" style="color:black">* Datos obligatorios</div>
          <button class="btn btn-responsive float-right" type="submit" id="botonInsertar" title="Insertar">Insertar</button><br>
          <a href="index.php">Volver</a>
        </div>
    </form><br><br>
</div>
</div>
</body>
</html>

<?php
}
else{ ?>
    <br>
    <div style="text-align: center;"><span style="color:#3498DB;  font-size: 40px;"><?php echo "No tiene permisos";?></span></div>
    <div style="text-align: center;"><span style="color:#3498DB;  font-size: 25px;"><?php echo "Sera redirigido";?></span></div>
    <meta http-equiv="refresh" content="2;url=../principal/home.php"/>
<?php   }
?>




<script type="text/javascript">
$('#botonInsertar').click(function(evento){
    var accion = document.getElementById('accion').value;
    if(accion == "nuevo"){
      pregunta = '¿Desea dar de alta el Producto?';
    }
    if(accion == "editar"){
      pregunta = '¿Desea actualizar el Producto?';
    }

    evento.preventDefault();
    if(validarFormulario()){
        Swal.fire({
            title: pregunta,
            width:'600px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3498DB',
            confirmButtonText: 'Aceptar',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value){
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
    
    if($("#pronombre").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar NOMBRE");
      $("#pronombre").focus();
      return false;
    }

    if($("#prodetalle").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar DETALLES");
      $("#prodetalle").focus();
      return false;
    }

    if($("#procantstock").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar Stock");
      $("#procantstock").focus();
      return false;
    }
    else{
        if($("#procantstock").val().includes('.')){
          return mensajeError("Error", "Formato de STOCK erroneo");
          $("#procantstock").focus(); 
          return false;
        }
        if(isNaN($("#procantstock").val())){
            return mensajeError("Error", "Formato de STOCK erroneo");
            $("#procantstock").focus(); 
            return false;
        }
        else{ 
          if($("#procantstock").val() < 0){
            return mensajeError("Error", "Formato de STOCK erroneo");
            $("#procantstock").focus(); 
            return false;
          }
        }
    }

    if($("#proprecio").val() == ""){
      return mensajeError("Faltan datos", "Debe ingresar Precio");
      $("#proprecio").focus();
      return false;
    }
    else{
        if($("#proprecio").val() < 0){
            return mensajeError("Error", "Formato de PRECIO erroneo");
            $("#proprecio").focus(); 
            return false;
        }
    }


  /*  if($('input[type="file"]').val()!=null){
        if($('input[type="file"]').val() != ''){ 
            var archivo= document.getElementById('archivo');
            if(archivo.files && archivo.files[0]){
                if((archivo.files[0].type != "image/png") && (archivo.files[0].type != "image/jpg") && (archivo.files[0].type != "image/jpeg")){
                    return mensajeError("Error de extension", "Permitido png, jpg y jpeg");
                    return false;
                }
            }
            else{
                return mensajeError("Error al cargar el archivo", "Intente mas tarde");
                return false;
            }
        }
        else{
            return mensajeError("Error", "Falta cargar imagen");
            return false;
        }
    }*/

    return true;
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

  function mensajeExito($mensaje1, $mensaje2){
    Swal.fire({
      type: 'success',
      title: $mensaje1,
      text: $mensaje2,
      confirmButtonColor: '#3498DB', 
      width:'600px',
      allowOutsideClick: false,
    });
    return false;
  }
 
</script>

