<?php
class AbmCompra{
    
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
        $obj = null;
        //print_r($param);
        if(array_key_exists('idcompra',$param) and  array_key_exists('cofecha',$param) and array_key_exists('idusuario',$param)){
            //print_r($param);
            $obj = new Compra();
            $obj->setear($param['idcompra'], $param['cofecha'], $param['idusuario']);
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
        
        if(isset($param['idcompra']) ){
            $obj = new Compra();
            $obj->setear($param['idcompra'], null, null);
        }
        return $obj;
    }
    
    
    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    
    //Igual
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idcompra']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    
    public function alta($param){
        //verEstructura($param); va bien
        //print_r($param);
        $resp = false;

        //Agregamos la fecha actual y el idcompra null, ya que es nueva compra
        $param['idcompra'] =null;
        $param['cofecha'] = date('Y-m-d H:i:s', time());

        $elObjtTabla = $this->cargarObjeto($param);
        //print_r($elObjtTabla);
        
        if ($elObjtTabla!=null and $elObjtTabla->insertar()){
            $resp = true;
        }
        //print_r($elObjtTabla);
        //return $elObjtTabla; //Devuelve la compra para poder obtener el id
        return $resp;
        
    }
    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    
    //Igual
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
    
    //Igual
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
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'";

            if  (isset($param['cofecha']))
                 $where.=" and cofecha ='".$param['cofecha']."'";

            if  (isset($param['idusuario']))
                 $where.=" and idusuario ='".$param['idusuario']."'";
        }

        $obj = new Compra();
        //echo $where;
        $arreglo = $obj->listar($where);
        //echo "Van ".count($arreglo);
        //print_r($arreglo);
        return $arreglo;
    }


    //Obtengo la ultima compra del usuario
    public function ultimaCompra($idusuario){
        $arreglo = array();
        $arrayAsociativo = ["idusuario" => $idusuario];
        $array = $this->buscar($arrayAsociativo);
        $arreglo = end($array);
        //print_r($arreglo);
        return $arreglo;
    }


    //Obtengo ID ultima compra del usuario
    public function idUltimaCompra($idusuario){
        $arreglo = array();
        $arrayAsociativo = ["idusuario" => $idusuario];
        $array = $this->buscar($arrayAsociativo);
        $arreglo = end($array);
        //print_r($arreglo);
        $idUltimaCompra = $arreglo->getIdcompra();
        return $idUltimaCompra;
    }
    
}
?>