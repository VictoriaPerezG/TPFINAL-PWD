<?php
class AbmUsuario{
    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Tabla
     */
    
    public function abm($datos){
        $resp = false;
        if($datos['accion']=='editar'){
            if($this->modificacion($datos)){
                $resp = true;
            }
        }
        if($datos['accion']=='editarPerfil'){
            if($this->modificacion2($datos)){
                $resp = true;
            }
        }
        if($datos['accion']=='borrar'){
            if($this->baja($datos)){
                $resp =true;
            }
        }
        if($datos['accion']=='nuevo'){
            if($this->alta($datos)){
                $resp =true;
            }
        }
        return $resp;
    }


    public function cargarObjeto($param){
        $obj = null;
        if(array_key_exists('idusuario',$param) and  array_key_exists('usnombre',$param) and array_key_exists('uspass',$param) and array_key_exists('usmail',$param) and array_key_exists('roles',$param)){

            //print_r($param);
            $obj = new Usuario();

            //$obj->setear($param['idusuario'], strtoupper($param['usnombre']), $param['uspass'], $param['usmail'], $param['usdeshabilitado']);

            if (array_key_exists('usdeshabilitado', $param)) {
                $obj->setear($param["idusuario"], $param["usnombre"], $param["uspass"], $param["usmail"], $param["usdeshabilitado"]);
            } else {
                $obj->setear($param["idusuario"], $param["usnombre"], $param["uspass"], $param["usmail"], NULL);
            }
        }
       // print_r($obj);
        return $obj;
    } 

    public function cargarObjeto2($param){
        $obj = null;
        //print_r($param);

        if(array_key_exists('idusuario',$param) and  array_key_exists('usnombre',$param) and array_key_exists('uspass',$param) and array_key_exists('usmail',$param)){

            //print_r($param);
            $obj = new Usuario();

            if (array_key_exists('usdeshabilitado', $param)) {
                $obj->setear($param["idusuario"], $param["usnombre"], $param["uspass"], $param["usmail"], $param["usdeshabilitado"]);
            } else {
                $obj->setear($param["idusuario"], $param["usnombre"], $param["uspass"], $param["usmail"], NULL);
            }
        }
        //print_r($obj);
        return $obj;
    }


    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Tabla
     */
    
    //Igual
    public function cargarObjetoConClave($param){
        $obj = null;
        
        if(isset($param['idusuario']) ){
            $obj = new Usuario();
            $obj->setear($param['idusuario'], null, null, null, null);
        }
        return $obj;
    }
    
    
    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    
    private function seteadosCamposClaves($param){
        //print_r($param);
        $resp = false;
            if (isset($param['idusuario'])){
                $resp = true;
            }
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    
    public function alta($param){
        //verEstructura($param);
        //print_r($param);
        $resp = false;
        $param['idusuario'] =null;
        $elObjtTabla = $this->cargarObjeto($param);
        //print_r($elObjtTabla);

        //Una vez que se pudo insertar el usuario, cargamos los roles
        if ($elObjtTabla!=null and $elObjtTabla->insertar()){
            if ($this->asignarRoles($param['roles'], $elObjtTabla->getIdusuario())) {
                $resp = true;
            }
        }

        return $resp;
        
    }
    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla!=null and $elObjtTabla->eliminar()){
                $resp = true;
            }
        }
        
        return $resp;
    }
    
    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    
    public function modificacion($param){
        //verEstructura($param);
        //print_r($param);
        $resp = false;
        if ($this->seteadosCamposClaves($param)){ //Aca va bien
            //print_r($param);
            $elObjAuto = $this->cargarObjeto($param);

            if($elObjAuto!=null and $elObjAuto->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }


    public function modificacion2($param){
        //verEstructura($param);
        //print_r($param);
        $resp = false;
        if ($this->seteadosCamposClaves($param)){ //Aca va bien
            //print_r($param);
            $elObjAuto = $this->cargarObjeto2($param);

            if($elObjAuto!=null and $elObjAuto->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    
    /**
     * permite buscar un objeto
     * @param array $param
     * @return boolean
     */

    //Si ingresa con $param NULL es para que muestre todos los productos
    
    //Igual
    public function buscar($param){ 
        //verEstructura($param); //todo bien
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario ='".$param['idusuario']."'";

            if  (isset($param['usnombre']))
                 $where.=" and usnombre ='".strtoupper($param['usnombre'])."'";

            if  (isset($param['uspass']))
                 $where.=" and uspass ='".$param['uspass']."'";

            if  (isset($param['usmail']))
                 $where.=" and usmail ='".$param['usmail']."'";
        }

        $obj = new Usuario();
        //echo $where;
        $arreglo = $obj->listar($where);
        //echo "Van ".count($arreglo); //Va bien
        //print_r($arreglo);
        return $arreglo;
    }

    //Le asignamos los roles elegidos al usuario
    public function asignarRoles($roles,$idusuario){
        $asignacion=false;
            foreach($roles as $rol){
                $objUsrrol = new UsuarioRol();
                $objUsrrol->setearConClave($idusuario, $rol);
                $asignacion = $objUsrrol->insertar(); 
            }
        return $asignacion;
    }


    public function darRoles($param){
        //print_r($param);
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario =".$param['idusuario'];
            if  (isset($param['idrol']))
                 $where.=" and idrol ='".$param['idrol']."'";
        }
        $obj = new UsuarioRol();
        $arreglo = $obj->listar($where);
        //echo "Van ".count($arreglo);
        return $arreglo;
    }

     public function darRoles2($arrayUsuarios){

        foreach ($arrayUsuarios as $rolUs) {
            $roles = [];
            //aca me devuelve el array de roles de cada usuario:
            foreach ($rolUs as $rolU) {
                echo $rolU->getobjrol()->getIdrol();
                $rol = $rolU->getobjrol()->getIdrol();
                array_push($roles, $rol);
            }
            //array_push($rolesId, $roles);
        }
        return $roles;
    }
    
}
?>