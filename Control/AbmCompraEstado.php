<?php
class AbmCompraEstado{
    
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
        if (array_key_exists('idcompraestado', $param) and array_key_exists('idcompra', $param)
            and array_key_exists('idcompraestadotipo', $param) and array_key_exists('cefechaini', $param)
            and array_key_exists('cefechafin', $param)){

            $objCompra = new Compra();
            $objCompra->setIdcompra($param['idcompra']);
            $objCompra->cargar();

            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->setIdCompraEstadoTipo($param['idcompraestadotipo']);
            $objCompraEstadoTipo->cargar();

            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], $objCompra, $objCompraEstadoTipo, $param['cefechaini'], $param['cefechafin']);
        }
        return $obj;
    }

     /**
     * @param array $param
     * @return CompraEstado
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idcompraestado'])) {
            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], null, null, null, null);
        }
        return $obj;
    }

    /**
     * @param array $param
     * @return boolean
     */
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idcompraestado']))
            $resp = true;
        return $resp;
    }

    /**
     * Inserta en la base de datos
     * @param array $param
     * @return boolean
     */
    public function alta($param){
        //print_r($param);
        $resp = false;
        $param['idcompraestado'] = null;
        $obj = $this->cargarObjeto($param);
        //print_r($obj);

        if ($obj != null and $obj->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $obj = $this->cargarObjetoConClave($param);
            if ($obj !=null and $obj->eliminar()){
                $resp = true;
            }
        }
        return $resp;
    } 

    /**
     * @param array $param
     * @return boolean
     */
    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null and $obj->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    /**
     * @param array $param
     * @return array
     */
    public function buscar($param)
    {
        //print_r($param);
        $where = " true ";
        if ($param != null) {
            if (isset($param['idcompraEstado']))
                $where .= " and idcompraEstado =" . $param['idcompraestado'];
            if (isset($param['idcompra']))
                $where .= " and idcompra =" . $param['idcompra'];
            if (isset($param['idcompraestadotipo']))
                $where .= " and idcompraestadotipo ='" . $param['idcompraestadotipo'] . "'";
            if (isset($param['cefechafin']))
                $where .= " and cefechafin ='" . $param['cefechafin'] . "'";
        }

        $obj = new CompraEstado();
        $arreglo = $obj->listar($where);
        //print_r($arreglo);
        return $arreglo;
    }

    public function buscarCompraBorrador($arrayCompra)
    {
        $objCompraEstadoInciada = null;
        $i = 0;
        /* Busca en el arraycompra si hay alguna que este con el estado "iniciada" */
        while (($objCompraEstadoInciada == null) && ($i < count($arrayCompra))) {
            $idCompra["idcompra"] = $arrayCompra[$i]->getIdCompra();
            $arrayCompraEstado = $this->buscar($idCompra);
            if ($arrayCompraEstado[0]->getCompraEstadoTipo()->getCetDescripcion() == "borrador") {
                $objCompraEstadoInciada = $arrayCompraEstado[0];
            } else {
                $i++;
            }
        }
        return $objCompraEstadoInciada;
    }

    public function buscarCompras($arrayCompra)
    {
        $arrayCompraIniciadas = [];
        /* Busca en el arraycompra si hay alguna que este con el estado "iniciada" */
        foreach($arrayCompra as $compra){
            $idCompra["idcompra"] = $compra->getIdCompra();
            $arrayCompraEstado = $this->buscar($idCompra);
            if ($arrayCompraEstado[0]->getCompraEstadoTipo()->getIdCompraEstadoTipo() >= 2) {
                array_push($arrayCompraIniciadas, $arrayCompraEstado[0]);
            }
        }
        return $arrayCompraIniciadas;
    }

}

?>