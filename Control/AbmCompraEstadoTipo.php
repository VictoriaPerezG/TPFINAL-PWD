<?php
class AbmCompraEstadoTipo{
    
    private function cargarObjeto($param){
        $obj = null;
        if (array_key_exists('idcompraestadotipo', $param) and array_key_exists('cetdescripcion', $param)
            and array_key_exists('cetdetalle', $param)){

            $obj = new CompraEstadoTipo();
            $obj->setear($param['idcompraestadotipo'], $param['cetdescripcion'], $param['cetdetalle']);
        }
        return $obj;
    }


    private function cargarObjetoConClave($param){
        $obj = null;
        if (isset($param['idcompraestadotipo'])) {
            $obj = new CompraEstadoTipo();
            $obj->setear($param['idcompraestadotipo'], null, null, null, null);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idcompraestadotipo']))
            $resp = true;
        return $resp;
    }

    public function alta($param){
        $resp = false;
        $param['idcompraestadotipo'] = null;
        $obj = $this->cargarObjeto($param);
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

    public function buscar($param){
        $where = " true ";
        if ($param != null) {
            if (isset($param['idcompraestadotipo']))
                $where .= " and idcompraestadotipo =" . $param['idcompraestadotipo'];
            if (isset($param['cetdetalle']))
                $where .= " and cetdetalle =" . $param['cetdetalle'];
            if (isset($param['cetdescripcion']))
                $where .= " and cetdescripcion ='" . $param['cetdescripcion'] . "'";
        }

        $obj = new CompraEstadoTipo();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

}

?>