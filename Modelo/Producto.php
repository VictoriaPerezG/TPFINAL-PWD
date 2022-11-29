<?php 
class Producto extends BaseDatos{
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $proestado;
    private $proprecio;
    private $mensajeoperacion;
    
    public function __construct(){
        parent::__construct();
        $this->idproducto="";
        $this->pronombre="";
        $this->prodetalle="";
        $this->procantstock= "";
        $this->proestado="";
        $this->proprecio="";
        $this->mensajeoperacion = "";
    }

    public function setear($idproducto, $pronombre, $prodetalle, $procantstock, $proestado, $proprecio){
        $this->setIdproducto($idproducto);
        $this->setPronombre($pronombre);
        $this->setProdetalle($prodetalle);
        $this->setProcantstock($procantstock);
        $this->setProestado($proestado);
        $this->setProprecio($proprecio);
    }

    public function getIdproducto(){
        return $this->idproducto;
    }

    public function setIdproducto($idproducto){
        $this->idproducto = $idproducto;
    }
    
    public function getPronombre(){
        return $this->pronombre; 
    }
    public function setPronombre($pronombre){
        $this->pronombre = $pronombre; 
    }

    public function getProdetalle(){
        return $this->prodetalle;  
    }
    public function setProdetalle($prodetalle){
        $this->prodetalle = $prodetalle;   
    }

    public function getProcantstock(){
        return $this->procantstock; 
    }
    public function setProcantstock($procantstock){
        $this->procantstock = $procantstock;
    }


    public function getProestado(){
        return $this->proestado; 
    }
    public function setProestado($proestado){
        $this->proestado = $proestado;
    }

    public function getProprecio(){
        return $this->proprecio;
    }
    public function setProprecio($proprecio){
        $this->proprecio = $proprecio;
    }

    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
        
    }
    public function setmensajeoperacion($valor){
        $this->mensajeoperacion = $valor;  
    }


    public function Buscar($idproducto){
        $resp = false;
        $sql="SELECT * FROM producto WHERE idproducto=".$idproducto;
        if($this->Iniciar()){
            if($this->Ejecutar($sql)){
                if($row = $this->Registro()){
                    $this->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['proestado'], $row['proprecio']);
                    $resp = true;
                }
            }else {
                $this->setmensajeoperacion("Tabla->buscar: ".$this->getError());
            }
        }else{
            $this->setmensajeoperacion("Tabla->buscar: ".$this->getError());
        }
        return $resp;
    }
    
    //Igual
    public function cargar(){
        $resp = false;
        $sql="SELECT * FROM producto WHERE idproducto = ".$this->getIdproducto();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $this->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['proestado'], $row['proprecio']);
                }
            }
        }
        else{
            $this->setmensajeoperacion("Tabla->listar: ".$base->getError());
        }
        return $resp;   
    }

    //Igual
    public function insertar(){
        $resp = false;
        $sql="INSERT INTO producto(pronombre, prodetalle, procantstock, proestado, proprecio) VALUES('".strtoupper($this->getPronombre())."', '".strtoupper($this->getProdetalle())."', '".$this->getProcantstock()."', '".$this->getProestado()."', '".$this->getProprecio()."');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdproducto($elid);
                $resp = true;
            } else {
                $this->setmensajeoperacion("Tabla->insertar: ".$this->getError());
            }
        } else {
            $this->setmensajeoperacion("Tabla->insertar: ".$this->getError());
        }
        return $resp;
    }

    //Igual
    public function modificar(){
        $resp = false;
        $sql="UPDATE producto SET pronombre='".$this->getPronombre()."',prodetalle='".$this->getProdetalle()."',procantstock='".$this->getProcantstock()."', proestado='".$this->getProestado()."', proprecio='".$this->getProprecio()."'".
        " WHERE idproducto=".$this->getIdproducto();
        
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
 
    //Igual
    public function eliminar(){
        $resp = false;
        $sql="DELETE FROM producto WHERE idproducto=".$this->getIdproducto();
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

    //Igual
    public function listar($parametro=""){
        $arreglo = array();
        $sql="SELECT * FROM producto ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    while ($row = $this->Registro()){
                        $producto= new Producto();
                        $producto->setIdproducto($row['idproducto']);
                        $producto->cargar(); 
                        array_push($arreglo, $producto);
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