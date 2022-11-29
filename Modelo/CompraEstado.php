<?php
include_once 'conector/BaseDatos.php';

class CompraEstado{
    private $idcompraestado;
    private $idcompra;
    private $compraestadotipo;
    private $cefechaini;
    private $cefechafin;
    private $mensajeOperacion;

    public function __construct(){
        $this->idcompraestado = "";
        $this->idcompra = new Compra();
        $this->compraestadotipo = new CompraEstadoTipo();
        $this->cefechaini = null;
        $this->cefechafin = null;
    }

    public function setear($idcompraestado, $idcompra, $compraestadotipo, $cefechaini, $cefechafin){
        $this->setIdCompraEstado($idcompraestado);
        $this->setCompra($idcompra);
        $this->setCompraEstadoTipo($compraestadotipo);
        $this->setCeFechaIni($cefechaini);
        $this->setCeFechaFin($cefechafin);
    }

    public function getIdCompraEstado(){return $this->idcompraestado;}
    public function setIdCompraEstado($idcompraestado){$this->idcompraestado = $idcompraestado;}
    
    public function getCeFechaIni(){return $this->cefechaini;}
    public function setCeFechaIni($cefechaini){$this->cefechaini = $cefechaini;}
    
    public function getCeFechaFin(){return $this->cefechafin;} 
    public function setCeFechaFin($cefechafin){$this->cefechafin = $cefechafin;}

    public function getCompra(){return $this->compra;}
    public function setCompra($compra){$this->compra = $compra;}

    public function getCompraEstadoTipo(){return $this->compraestadotipo;}
    public function setCompraEstadoTipo($compraestadotipo){$this->compraestadotipo = $compraestadotipo;}
    
    public function getMensajeOperacion(){return $this->mensajeOperacion;}
    public function setMensajeOperacion($mensajeOperacion){$this->mensajeOperacion = $mensajeOperacion;}

    

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestado WHERE idcompraestado = " .$this->getIdCompraEstado();
        
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            
            if ($res > -1) {
                if ($res > 0){
                    $row = $base->Registro();
                    
                    $compra = null;
                    if ($row['idcompra'] != null) {
                        $compra = new Compra();
                        $compra->setIdcompra($row['idcompra']);
                        $compra->cargar();
                    }
                    $compraEstadoTipo = null;
                    if ($row['idcompraestadotipo'] != null) {
                        $compraEstadoTipo = new CompraEstadoTipo();
                        $compraEstadoTipo->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                        $compraEstadoTipo->cargar();
                    }
                    $resp =  true;
                    $this->setear($row['idcompraestado'],$compra,$compraEstadoTipo,$row['cefechaini'],$row['cefechafin']);
                }
            }
        } else {
            $this->setMensajeOperacion("menu->listar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraestado (idcompra,idcompraestadotipo,cefechaini,cefechafin)  VALUES (
                ".$this->getCompra()->getIdcompra().",
                ".$this->getCompraEstadoTipo()->getIdCompraEstadoTipo().",
                '".$this->getCeFechaIni()."',
                '".$this->getCeFechaFin()."'
                )";

        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("compraestado->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("compraestado->insertar: " . $base->getError());
        }
        return $resp;
    }


    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compraestado SET 
                idcompraestado=".$this->getIdCompraEstado().", 
                idcompra=".$this->getCompra()->getIdCompra().", 
                idcompraestadotipo=".$this->getCompraEstadoTipo()->getIdCompraEstadoTipo().", 
                cefechafin='".$this->getCeFechaFin()."', 
                cefechaini='".$this->getCeFechaIni()."'
                WHERE idcompraestado=".$this->getIdCompraEstado();
        
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("CompraEstado->modificar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("CompraEstado->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compraestado WHERE idcompraestado=" . $this->getIdCompraEstado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                return true;
            } else {
                $this->setmensajeoperacion("CompraEstado->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("CompraEstado->eliminar: " . $base->getError());
        }
        return $resp;
    }
    
    public function listar($parametro = ""){
        $arreglo = null;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestado ";
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                $arreglo = array();
                while ($row = $base->Registro()) {
                    $obj = new CompraEstado();
                    /*$objCompra = null;
                    if ($row['idcompra'] != null) {
                        $objCompra = new Compra();
                        $objCompra->setIdCompra($row['idcompra']);
                        $objCompra->cargar();
                    }
                    $objCompraEstadoTipo = null;
                    if ($row['idcompraestadotipo'] != null) {
                        $objCompraEstadoTipo = new CompraEstadoTipo();
                        $objCompraEstadoTipo->setIdCompraEstadoTipo($row['idcompraestadotipo']);
                        $objCompraEstadoTipo->cargar();
                    }

                    $obj->setear($row['idcompraestado'], $objCompra, $objCompraEstadoTipo, $row['cefechaini'], $row['cefechafin']);*/

                    
                    $obj->setIdCompraEstado($row['idcompraestado']);
                    $obj->cargar();

                    array_push($arreglo, $obj);
                }
            }
        } else {
            $this->setMensajeOperacion("CompraEstado->listar: " . $base->getError());
        }
        return $arreglo;
    }
}
?>