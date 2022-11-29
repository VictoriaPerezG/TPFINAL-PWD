<?php

class CompraEstadoTipo{
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeOperacion;

    public function __construct(){
        $this->idcompraestadotipo = "";
        $this->cetdescripcion = "";
        $this->cetdetalle = "";
        $this->mensajeOperacion = "";
    }

    public function getIdCompraEstadoTipo(){return $this->idcompraestadotipo;}
    public function setIdCompraEstadoTipo($idcompraestadotipo){$this->idcompraestadotipo = $idcompraestadotipo;}
    public function getCetDescripcion(){return $this->cetdescripcion;}
    public function setCetDescripcion($cetdescripcion){$this->cetdescripcion = $cetdescripcion;}
    
    public function getCetDetalle(){return $this->cetdetalle;}
    public function setCetDetalle($cetdetalle){$this->cetdetalle = $cetdetalle;}
    
    public function getMensajeOperacion(){return $this->mensajeOperacion;}
    public function setMensajeOperacion($mensajeOperacion){$this->mensajeOperacion = $mensajeOperacion;}

    public function setear($idcompraestadotipo, $cetdescripcion, $cetdetalle){
        $this->setIdCompraEstadoTipo($idcompraestadotipo);
        $this->setCetDescripcion($cetdescripcion);
        $this->setCetDetalle($cetdetalle);
    }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = " . $this->getIdCompraEstadoTipo();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    $resp = true;
                }
            }
        } else {
            $this->setmensajeoperacion("CompraEstadoTipo->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraestadotipo (cetdescripcion, cetdetalle) VALUES (
                '" . $this->getCetDescripcion() . "',
                '" . $this->getCetDetalle() . "');";
        
        if ($base->Iniciar()) {
            if ($base = $base->Ejecutar($sql)) {
                $this->setIdCompraEstadoTipo($base);
                $resp = true;
            } else {
                $this->setMensajeOperacion("CompraEstadoTipo->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compraestadotipo SET 
                idcompraestadotipo='" . $this->getIdCompraEstadoTipo() . "', 
                cetdescripcion='" . $this->getCetDescripcion() . "', 
                cetdetalle='" . $this->getCetDetalle() . "' 
                WHERE idcompraestadotipo='" . $this->getIdCompraEstadoTipo() . "'";
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("CompraEstadoTipo->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("CompraEstadoTipo->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo=" . $this->getIdCompraEstadoTipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setmensajeoperacion("CompraEstadoTipo->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("CompraEstadoTipo->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = ""){
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new CompraEstadoTipo();
                    $obj->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            $this->setMensajeOperacion("CompraEstadoTipo->listar: " . $base->getError());
        }

        return $arreglo;
    }

}

?>
