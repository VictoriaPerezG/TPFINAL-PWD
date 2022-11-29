<?php
class AbmMenurol{


    public function cargarObjeto($param){

        $objMenuRol = null;
        $objMenu = null;
        $objRol = null;

        if (array_key_exists('idMenu', $param) and array_key_exists('idRol', $param)) {
            $objMenu = new Menu();
            $objMenu->setIdMenu($param['idMenu']);
            $objRol = new Rol();
            $objRol->setIdRol($param['idRol']);
            $objMenuRol = new MenuRol();
            $objMenuRol->setear($objMenu, $objRol);
        }
        return $objMenuRol;
    }

    private function cargarObjetoConClave($param){
        $obj = null;
        if (isset($param['idMenu'])) {
            $obj = new MenuRol();
            $obj->setear($param['idMenu'], null);
        }
        return $obj;
    }


    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idMenu'], $param['idRol']))
            $resp = true;
        return $resp;
    }

    public function alta($param){
        $resp = false;
        $objMenuRol = $this->cargarObjeto($param);

        if ($objMenuRol != null and $objMenuRol->insertar()) {
            $resp = true;
        }
        return $resp;
    }


    public function baja($param){
        $resp = false;

        if ($this->seteadosCamposClaves($param)) {
            $objMenuRol = $this->cargarObjetoConClave($param);
            if ($objMenuRol != null and $objMenuRol->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    public function modificacion($param){

        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $objMenuRol = $this->cargarObjeto($param);
            if ($objMenuRol != null and $objMenuRol->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    public function buscar($param){
        $where = " true ";
        if ($param != null) {
            if (isset($param['idmenu']))
                $where .= " and idmenu =" . $param['idmenu'];
            if (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
        }

        $objMenuRol = new MenuRol();
        $arreglo = $objMenuRol->listar($where);
        return $arreglo;
    }

    public function menusRol($objRol){
        $param['idrol'] = $objRol->getIdRol();
        $objMenu = $this->buscar($param);
        $menuesRoles = [];
        foreach ($objMenu as $obj) {
            if (is_array($obj)) {
                foreach ($obj as $objM) {
                    array_push($menuRol, $objM);
                }
            } else {
                array_push($menuRol, $obj);
            }
        }
        $menues = [];
        foreach ($menuesRoles as $objetos) {
            array_push($menues, $objetos->getMenu());
        }
        return $menues;
    }
}
