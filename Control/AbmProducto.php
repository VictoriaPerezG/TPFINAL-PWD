<?php
class AbmProducto{
    
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

    //Igual
    private function cargarObjeto($param){
        $obj = null;
           //print_r($param);
        //array_key_exists('idproducto',$param) and 
        if(array_key_exists('idproducto',$param) and  array_key_exists('pronombre',$param) and array_key_exists('prodetalle',$param) and array_key_exists('procantstock',$param) and array_key_exists('proestado',$param) and array_key_exists('proprecio',$param)){

           // print_r($param);
            $obj = new Producto();
            $obj->setear($param['idproducto'], strtoupper($param['pronombre']), $param['prodetalle'], $param['procantstock'], $param['proestado'], $param['proprecio']);
        }
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
        
        if(isset($param['idproducto']) ){
            $obj = new Producto();
            $obj->setear($param['idproducto'], null, null, null, null, null);
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
        if (isset($param['idproducto']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    
    //Igual
    public function alta($param){
        //verEstructura($param);
        //print_r($param);
        $resp = false;
        $param['idproducto'] =null;
        $elObjtTabla = $this->cargarObjeto($param);
        //print_r($elObjtTabla);
        
        if ($elObjtTabla!=null and $elObjtTabla->insertar()){
            $resp = true;
        }
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
            if  (isset($param['idproducto']))
                $where.=" and idproducto ='".$param['idproducto']."'";

            if  (isset($param['pronombre']))
                 $where.=" and pronombre ='".$param['pronombre']."'";

            if  (isset($param['prodetalle']))
                 $where.=" and prodetalle ='".$param['prodetalle']."'";

            if  (isset($param['procantstock']))
                 $where.=" and procantstock ='".$param['procantstock']."'";

            if  (isset($param['proestado']))
                 $where.=" and proestado ='".$param['proestado']."'";

            if  (isset($param['proprecio']))
                 $where.=" and proprecio ='".$param['proprecio']."'";
        }

        $obj = new Producto();
        //echo $where;
        $arreglo = $obj->listar($where);
        //echo "Van ".count($arreglo);
        return $arreglo;
    }
    
}
?>