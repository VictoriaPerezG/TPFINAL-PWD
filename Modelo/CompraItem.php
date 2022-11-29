<?php 
class CompraItem extends BaseDatos{
    private $idcompraitem;
    private $objProducto;
    private $objCompra;
    private $cicantidad;
    private $mensajeoperacion;
    
    public function __construct(){
        parent::__construct();
        $this->idcompraitem="";
        $this->objProducto= new Producto;
        $this->objCompra = new Compra;
        $this->cicantidad="";
        $this->mensajeoperacion = "";
    }

    public function setear($idcompraitem, $objProducto, $objCompra, $cicantidad){
        $this->setIdcompraitem($idcompraitem);
        $this->setObjproducto($objProducto);
        $this->setObjcompra($objCompra);
        $this->setCantidad($cicantidad);
    }

    public function getIdcompraitem(){return $this->idcompraitem;}
    public function setIdcompraitem($idcompraitem){$this->idcompraitem = $idcompraitem;}
    
    public function getObjproducto(){return $this->objProducto; }
    public function setObjproducto($objProducto){$this->objProducto = $objProducto; }

    public function getObjcompra(){return $this->objCompra; }
    public function setObjcompra($objCompra){$this->objCompra = $objCompra; }

    public function getCantidad(){return $this->cicantidad;}
    public function setCantidad($cicantidad){$this->cicantidad = $cicantidad;}

    public function getmensajeoperacion(){return $this->mensajeoperacion;}
    public function setmensajeoperacion($valor){$this->mensajeoperacion = $valor;  }

    
    /*public function buscar($idcompraitem){
        $resp = false;
        $sql="SELECT * FROM compraitem WHERE idcompraitem=".$idcompraitem;
        if($this->Iniciar()){
            if($this->Ejecutar($sql)){
                if($row = $this->Registro()){

                    $this->setear($row['idcompraitem'], $row['cantidad']);
                    $resp = true;
                }
            }else {
                $this->setmensajeoperacion("Tabla->buscar: ".$this->getError());
            }
        }else{
            $this->setmensajeoperacion("Tabla->buscar: ".$this->getError());
        }
        return $resp;
    }*/
    
    public function cargar(){
        $resp = false;
        $sql="SELECT * FROM compraitem WHERE idcompraitem = ".$this->getIdcompraitem();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $producto=new Producto();
                    $producto->setIdproducto($row['idproducto']);
                    $producto->cargar();
                    $compra=new Compra();
                    $compra->setIdcompra($row['idcompra']);
                    $compra->cargar();
                    $this->setear($row['idcompraitem'], $producto, $compra, $row['cicantidad']);
                }
            }
        }
        else{
            $this->setmensajeoperacion("Tabla->listar: ".$base->getError());
        }
        return $resp;   
    }


    public function insertar(){
        $resp = false;
        $sql="INSERT INTO compraitem(idproducto, idcompra, cicantidad) VALUES (
        ".$this->getObjproducto()->getIdproducto().", 
        ".$this->getObjcompra()->getIdcompra().", 
        '".$this->getCantidad()."')";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdcompraitem($elid);
                $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->insertar: ".$this->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->insertar: ".$this->getError());
        }
        return $resp;
    }


    public function modificar(){
        $resp = false;

        $sql="UPDATE compraitem SET idproducto='".$this->getObjproducto()->getIdproducto()."', ".
            " idCompra=".$this->getObjcompra()->getIdcompra().", ".
            " cantidad=".$this->getCantidad().
            " WHERE idcompraitem=".$this->getIdcompraitem();
        
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->modificar: ".$this->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->modificar: ".$this->getError());
        }
        return $resp;
    }
 
    public function eliminar(){
        $resp = false;
        $sql="DELETE FROM compraitem WHERE idcompraitem=".$this->getIdcompraitem();
        if ($this->Iniciar()) {
            if ($this->Ejecutar($sql)) {
                return true;
            } else {
                $this->setmensajeoperacion("Tabla->eliminar: ".$this->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->eliminar: ".$this->getError());
        }
        return $resp;
    }

    public function listar($parametro=""){
        $arreglo = array();
        $sql="SELECT * FROM compraitem ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    while ($row = $this->Registro()){
                        $obj= new CompraItem();
                        $obj->setIdcompraitem($row['idcompraitem']);
                        $obj->cargar();
                        array_push($arreglo, $obj);
                        
                        /*$producto=new Producto();
                        $producto->set($row['idproducto']);
                        $producto->cargar();

                        $compra=new Compra();
                        $compra->set($row['idcompra']);
                        $compra->cargar();

                        $this->setear($row['idcompraitem'], $producto, $compra, $row['cantidad']);
                        array_push($arreglo, $obj);*/
                    }
                }
            } else {
                $this->setmensajeoperacion("Tabla->listar: ".$this->getError());
            }
        }
        return $arreglo;
    }
}

?>