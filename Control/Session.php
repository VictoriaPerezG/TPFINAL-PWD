<?php
class Session{
    //private $objUsuario;       

    public function __construct(){
        if (!session_start()) {
            return false;
        } else {
            return true;
        }
    }


    public function setObjUsuario($objUsuario){
        $this->objUsuario = $objUsuario;
    }
    public function getObjUsuario(){
        return $this->objUsuario;
    }
   
    /**
     * Actualiza las variables de sesión con los valores ingresados.
     */
    public function iniciar($nombreUsuario,$psw){
        $resp = false;
        $obj = new AbmUsuario();
        $param['usnombre']=$nombreUsuario;
        $param['uspass']=$psw;
        $param['usdeshabilitado']='null';

        $resultado = $obj->buscar($param);
        if(count($resultado) > 0){
            $usuario = $resultado[0];
            $_SESSION['idusuario']=$usuario->getidusuario();
            $resp = true;
        } else {
            $this->cerrar();
        }
        return $resp;
    }
    
    /**
     * Valida si la sesión actual tiene usuario y psw válidos. Devuelve true o false.
     */
    public function validar(){
        $resp = false;
        if($this->activa() && isset($_SESSION['idusuario']))
            $resp=true;
        return $resp;
    }
    
    /**
     *Devuelve true o false si la sesión está activa o no.
     */
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


    public function estaActiva(){
        $resp=isset($_SESSION["usnombre"])? TRUE : FALSE;
        return $resp;
    }
   
    /**
     * Devuelve el usuario logeado.
     */
    public function getUsuario(){
        $usuario = null;
        if($this->validar()){
            $obj = new AbmUsuario();
             $param['idusuario']=$_SESSION['idusuario'];
             $resultado = $obj->buscar($param);
            if(count($resultado) > 0){
                $usuario = $resultado[0];
            }
        }
        return $usuario;
    }

     /**
     * Devuelve el rol del usuario logeado.
     */
    public function getRol(){
        $list_rol = null;
        if($this->validar()){
            $obj = new AbmUsuario();
             $param['idusuario']=$_SESSION['idusuario'];
             $resultado = $obj->darRoles($param);
            if(count($resultado) > 0){
                $list_rol = $resultado;
            }
        }
        return $list_rol;

    }

    public function getRoles(){

        $roles = $this->getRol();
        $rolesUser = [];
        foreach ($roles as $rol) {
            $rolU = $rol->getobjrol()->getIdrol();
            array_push($rolesUser, $rolU);
        }
        return $rolesUser;
    }
    
    /**
     *Cierra la sesión actual.
     */
    public function cerrar(){
        $resp = true;
        session_destroy();
       // $_SESSION['idusuario']=null;
        return $resp;
    }
   


    public function validaDatos($param){
        print_r($param);
        $arrayUsuario = $this->getObjUsuario()->buscar($param);
        $resp = false;
        if($arrayUsuario != null){
            $resp = true;
        }
        return $resp;
    }

    public function tieneAcceso($link){
        $salida=false;
        $roles = $this->getRoles(); //Obtengo los roles del usuario
        
        $objRol = new AbmRol();
        $objRoles = $objRol->obtenerObjeto($roles); //OBTENGO UN ARRAY DE OBJETOS ROLES
        $objMenuRol = new AbmMenurol();
        $objMenus = $objMenuRol->menusRol($objRoles); //OBTENGO UN ARRAY DE MENUS
       
        $contador = count($objMenus);
        $i=0;
        while(($i<$contador)&&(!$salida)){
            $linkAcceso = $objMenus[$i]->getMedescripcion();
            if (strcmp($linkAcceso, $link) === 0){
                $salida=true;
            }
            $i++;
        }
        return $salida;
         
    }
}
