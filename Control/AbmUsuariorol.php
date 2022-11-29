<?php
class AbmUsuariorol{
    
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

    private function cargarObjeto($param){
        $objUsuarioRol = null;
        $objRol = null;
        $objUsuario = null;

        if (array_key_exists('idusuario', $param) && array_key_exists('idrol', $param)) {
            $objRol = new Rol();
            $objRol->setIdrol($param['idrol']);
            $objUsuario = new Usuario();
            $objUsuario->setIdusuario($param['idusuario']);
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->setear($objUsuario, $objRol);
        }
        return $objUsuarioRol;
    }

    public function cargarObjetoConClave($param){
        $obj = null;
        
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $objUsuarioRol = new UsuarioRol();
            $objUsuarioRol->setear($param['idusuario'], $$param['idrol']);
        }
        return $obj;
    }
    
 
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idusuario']) && isset($param['idrol'])) {
            $resp = true;
        }
        return $resp;
    }
    
    public function alta($param){
        //verEstructura($param);
        //print_r($param);
        $resp = false;
        $param['idusuario'] =null;
        $param['idrol'] =null;
        $elObjtTabla = $this->cargarObjeto($param);
        //print_r($elObjtTabla);
        
        if ($elObjtTabla!=null and $elObjtTabla->insertar()){
            $resp = true;
        }
        return $resp;
        
    }

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
    
    public function modificacion($param){
        //verEstructura($param);
        //print_r($param);
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            //print_r($param);
            $elObjAuto = $this->cargarObjeto($param);
            //verEstructura($elObjAuto);

            if($elObjAuto!=null and $elObjAuto->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }
    

    //Si ingresa con $param NULL es para que muestre todos
    public function buscar($param){ 
        //verEstructura($param); //todo bien
        $where = " true ";
        if ($param<>NULL){
            if  (isset($param['idusuario']))
                $where.=" and idusuario ='".$param['idusuario']."'";

            if  (isset($param['idrol']))
                 $where.=" and idrol ='".$param['idrol']."'";
        }

        $obj = new UsuarioRol();
        //echo $where;
        $arreglo = $obj->listar($where);
        //echo "Van ".count($arreglo);
        return $arreglo;
    }
    
}
?>