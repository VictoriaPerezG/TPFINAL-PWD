<?php
include_once 'conector/BaseDatos.php';

class MenuRol{
    private $idmenu;
    private $idrol;
    private $mensajeOperacion;

    
    public function setRol($idrol){$this->idrol = $idrol;}
    public function setMenu($idmenu){$this->idmenu = $idmenu;}
    public function setMensajeOperacion($mensajeOperacion){$this->mensajeOperacion = $mensajeOperacion;}
    public function getRol(){return $this->idrol;}
    public function getMenu(){return $this->idmenu;}
    public function getMensajeOperacion(){return $this->mensajeOperacion;}

    public function __construct(){
        $this->idmenu = new Menu();
        $this->idrol = new Rol();
        $this->mensajeOperacion = "";
    }

    public function setear($idmenu, $idrol) {
        $this->setMenu($idmenu);
        $this->setRol($idrol);
    }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();

        $sql = "SELECT * FROM usuariorol WHERE 
                idrol = " . $this->getRol()->getIdRol() . "
                and idmenu =" . $this->getMenu()->getIdMenu();

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);

            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();

                    $menu = null;
                    if ($row['idmenu'] != null) {
                        $menu = new Menu();
                        $menu->setIdMenu($row['idmenu']);
                        $menu->cargar();
                    }

                    $rol = null;
                    if ($row['idrol'] != null) {
                        $rol = new Rol();
                        $rol->setIdRol($row['idrol']);
                        $rol->cargar();
                    }
                    $this->setear($menu, $rol);

                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("menurol->listar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menurol (idmenu,idrol)  VALUES ("
            . $this->getMenu()->getIdMenu() . ","
            . $this->getRol()->getIdRol() . "
                )";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("menurol->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("menurol->insertar: " . $base->getError());
        }
        return $resp;
    }


    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menurol WHERE 
                idmenu = " . $this->getMenu()->getIdMenu()
            . "and idrol =" . $this->getRol()->getIdRol();

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("menurol->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("menurol->eliminar: " . $base->getError());
        }
        return $resp;
    }


    public function listar($parametro = ""){
        $arreglo = null;
        $base = new BaseDatos();
        $sql = "SELECT * FROM menurol ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                $arreglo = array();
                while ($row = $base->Registro()) {

                    $menu = null;
                    if ($row['idmenu'] != null) {
                        $menu = new Menu();
                        $menu->setIdMenu($row['idmenu']);
                        $menu->cargar();
                    }

                    $rol = null;
                    if ($row['idrol'] != null) {
                        $rol = new Rol();
                        $rol->setIdRol($row['idrol']);
                        $rol->cargar();
                    }

                    $obj = new MenuRol();
                    $obj->setear($menu, $rol);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
