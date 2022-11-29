<?php
class AbmCompraitem{
    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Tabla
     */
    
    public function abm($datos){
        //print_r($datos);
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
        if(array_key_exists('idcompraitem',$param) and  array_key_exists('idproducto',$param) and array_key_exists('idcompra',$param) and array_key_exists('cicantidad',$param)){

            $objProducto = new Producto();
            $objProducto->setIdproducto($param['idproducto']);
            $objProducto->cargar();

            $objCompra = new Compra();
            $objCompra->setIdcompra($param['idcompra']);
            $objCompra->cargar();

            //print_r($param);
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], $objProducto, $objCompra, $param['cicantidad']);
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
        
        if(isset($param['idcompraitem']) ){
            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], null, null, null);
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
        if (isset($param['idcompraitem']))
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
        $param['idcompraitem'] =null;
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
            if  (isset($param['idcompraitem']))
                $where.=" and idcompraitem ='".$param['idcompraitem']."'";

            if  (isset($param['idproducto']))
                 $where.=" and idproducto ='".$param['idproducto']."'";

            if  (isset($param['idcompra']))
                 $where.=" and idcompra ='".$param['idcompra']."'";

             if  (isset($param['cicantidad']))
                 $where.=" and cicantidad ='".$param['cicantidad']."'";
        }

        $obj = new CompraItem();
        //echo $where;
        $arreglo = $obj->listar($where);
        //echo "Van ".count($arreglo);
        return $arreglo;
    }
    
}
?>