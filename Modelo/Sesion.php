<?php
class Sesion{
    private $objUsuario;
    private $listaRoles;
    private $mensajeoperacion;
    
    public function __construct(){
        session_start();
        /*if(session_start()){
            $this->objUsuario=null;
            $this->listaRoles=[];
            $this->mensajeoperacion="";
        }*/
    }
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }

    public function getListaRoles()
    {
        return $this->listaRoles;
    }

    public function setListaRoles($listaRoles)
    {
        $this->listaRoles = $listaRoles;
    }

    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }
    public function validar(){
        $inicia=false;
        if(isset($_SESSION['idusuario'])){
           $inicia=true;
        }
        return $inicia;
    }

    public function iniciar($usu,$pass){
        $salida = false;
        $abmUsuario=new AbmUsuario();
        $where =['usnombre'=>$usu,'uspass'=>$pass];
        $listaUsuarios=$abmUsuario->buscar($where);
        //print_r($listaUsuarios); va bien
        if($listaUsuarios>=1){
            //echo "Ingreso aca"; va bien
            if($this->activa()){
            //echo $listaUsuarios[0]->getIdusuario()."***".$listaUsuarios[0]->getUsnombre();
            //Va bien
                $_SESSION['idusuario']=$listaUsuarios[0]->getIdusuario();
                $_SESSION['usnombre']=$listaUsuarios[0]->getUsnombre();
                $salida = true;
            }
        }
        //print_r($_SESSION);
        //Array ([idusuario] => 1,[usnombre] => ADMIN)
        //return $_SESSION;
        return $salida;
    }
    
    /*public function activa(){
        $activa=false;
        if(session_start()){
            $activa=true;
        }
        return $activa;
    }*/

    public function activa(){
        $resp = false;
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                $resp = session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                $resp = session_id() === '' ? FALSE : TRUE;
            }
        }
        return $resp;
    }

    /*public function estaActiva(){
        $resp=isset($_SESSION["usnombre"])? TRUE : FALSE;
        return $resp;
    }*/
    
    public function getUsuario(){
        $abmUsuario=new AbmUsuario();
        $where =['usnombre'=>$_SESSION['usnombre'],'idusuario'=>$_SESSION['idusuario']];
        $listaUsuarios=$abmUsuario->buscar($where);
        if($listaUsuarios>=1){
            $usuarioLog=$listaUsuarios[0];
        }
        return $usuarioLog;
    }
    
    public function getRol(){
        $abmRol=new abmRol();
        $abmUsuarioRol=new AbmUsuarioRol();
        $usuario=$this->getUsuario();
        $idUsuario=$usuario->getIdUsuario();
        $param=['idusuario'=>$idUsuario];
        $listaRolesUsu=$abmUsuarioRol->buscar($param);
        if($listaRolesUsu>1){
            $rol=$listaRolesUsu;
        }else{
            $rol=$listaRolesUsu[0];
        }
        return $rol; 
    }
    
    public function cerrar(){
        $cerrar=false;
        if(session_destroy()){
            $cerrar=true;
        }
        return $cerrar;
    }
} 