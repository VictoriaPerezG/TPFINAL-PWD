<?php
include_once '../../configuracion.php';

$objTrans = new Session();
$resp = $objTrans->validar();

if($resp){
  $usuario = $objTrans->getUsuario()->getUsnombre();
  $conSession = true;
  $roles = $objTrans->getRoles();//Obtengo los roles del usuario
  $objRol = new AbmRol();
  $objRoles = $objRol->obtenerObjeto($roles); //OBTENGO UN ARRAY DE OBJETOS ROLES
  $objMenuRol = new AbmMenurol();
  $objMenus = $objMenuRol->menusRol($objRoles); //OBTENGO UN ARRAY DE MENUS

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//ES" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
  <title></title>
  <link rel="icon" type="image/png" href="../img/logo.ico"/>
  <link rel="stylesheet" type="text/css" href="../bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../sweetalert/sweetalert2.min.css">
  <script type="text/javascript" src="../sweetalert/sweetalert2.min.js"></script>
  <script type="text/javascript" src="../jquery-3.5.1.js"></script>
  
</head>
<body class="fondo"><br><br>
  
<div class="container">
     <nav class="navbar bg-primary navbar-expand-lg navbar-light py-4 text-center">
        <div class="container px-4 px-lg-5">
            <h1 class=" text-white ms-lg-1" style="margin-right: 50px">EyeShop</h1>
            
      <?php if($conSession){  
        ?>
            <div class="collapse navbar-collapse" id="navbarNav">
            <a class="px-2 mx-1 btn btn-lg btn-outline-dark" href="../principal/home.php" style="color: white;">Home</a>

          <?php
          foreach ($objMenus as $objMenu){
          ?>
              <a href="<?php echo $objMenu->getMedescripcion(); ?>" class="px-2 mx-1 btn btn-lg btn-outline-dark" style="color: white; text-decoration:none"><?php echo $objMenu->getMenombre();?></a>
          <?php
          }
          ?>

          <div style="margin-left:100px; margin-right:25px;"><span style="color: white";><?php echo "Usuario: ".$usuario?></span></div>

          <div style="text-align: right;">
            <button class="px-2 mx-1 btn btn-lg btn-outline-dark" type="button" id="botonSalir" title="cerrar">Salir</button>
          </div>

 <?php }else{?>
            <div style="text-align: right;">
              <a href='../sesion/iniciarSesion.php'>INGRESAR</a>
            </div>
<?php } ?>

      </div>
    </div>
  </nav>
  </div>
  </body>
  </html>
<?php
  } 
?>

<style type="text/css">
.fondo{
   background-image: url("../img/fondo5.jpg");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    background-attachment: fixed;
}
</style>


<script type="text/javascript">

$('#botonSalir').click(function(evento){
    pregunta = '¿Desea cerrar sesion?';
    evento.preventDefault();
        Swal.fire({
            title: pregunta,
            width:'600px',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3498DB',
            cancelButtonColor: 'red',
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value){
                $.ajax({
                    type:'POST',
                    url: '../estructura/cerrarSesion.php',
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
            }
        });
});


  function mensajeError($mensaje1, $mensaje2){
    swal.fire({
      type: 'error',
      title: $mensaje1,
      text: $mensaje2,
      allowOutsideClick: false,
      width:'600px',
      imageWidth: '160px',
    }).then(function(){
        window.location.replace("../pagina/index.php");
    });
  }

  function mensajeExito($mensaje1, $mensaje2){
    Swal.fire({
      type: 'success',
      title: $mensaje1,
      text: $mensaje2,
      confirmButtonColor: '#3498DB', 
      width:'600px',
      allowOutsideClick: false,
    }).then(function(){
        window.location.replace("../pagina/index.php");
    });
  }
</script>