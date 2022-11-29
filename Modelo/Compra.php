<?php 
class Compra extends BaseDatos{
    private $idcompra;
    private $cofecha;
    private $objUsuario;
    private $mensajeoperacion;
    
    public function __construct(){
        parent::__construct();
        $this->idcompra="";
        $this->cofecha="";
        $this->objUsuario = new Usuario;
        $this->mensajeoperacion = "";
    }

    public function setear($idcompra, $cofecha, $objUsuario){
        $this->setIdcompra($idcompra);
        $this->setCofecha($cofecha);
        $this->setObjusuario($objUsuario);
        //$this->objUsuario->setIdusuario($idUsuario);
    }

    /*public function setear($idCompra, $fecha, $idUsuario){
        $resp = false;
        $this->objUsuario->setIdusuario($idUsuario);
        if ($this->objUsuario->cargar()) {
            echo $idCompra." **** ".$fecha;
            $this->setIdcompra($idCompra);
            $this->setCofecha($fecha);
            $resp = true;
        }
        return $resp;
    }*/

    public function getIdcompra(){return $this->idcompra; }
    public function setIdcompra($idcompra){$this->idcompra = $idcompra;}
    
    public function getCofecha(){return $this->cofecha; }
    public function setCofecha($cofecha){$this->cofecha = $cofecha; }

    public function getObjusuario(){return $this->objUsuario; }
    public function setObjusuario($objUsuario){$this->objUsuario = $objUsuario; }

    public function getmensajeoperacion(){return $this->mensajeoperacion;}
    public function setmensajeoperacion($valor){$this->mensajeoperacion = $valor;  }



    public function Buscar($idrol){
        $resp = false;
        $sql="SELECT * FROM compra WHERE idcompra=".$idrol;
        if($this->Iniciar()){
            if($this->Ejecutar($sql)){
                if($row = $this->Registro()){

                    $this->setear($row['idcompra'], $row['cofecha']);
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
    
    public function cargar(){
        $resp = false;
        $sql="SELECT * FROM compra WHERE idcompra = ".$this->getIdcompra();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $usuario=new Usuario();
                    $usuario->setIdusuario($row['idusuario']);
                    $usuario->cargar();
                    $this->setear($row['idcompra'], $row['cofecha'], $usuario);
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
        $sql="INSERT INTO compra(cofecha, idusuario) VALUES('".$this->getCofecha()."', '".$this->getObjusuario()."');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdcompra($elid);
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

        $sql="UPDATE compra SET cofecha='".$this->getCofecha()."', ".
            " idUsuario=".$this->getObjusuario()->getIdusuario().
            " WHERE idcompra=".$this->getIdcompra();
        
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
        $sql="DELETE FROM compra WHERE idcompra=".$this->getIdcompra();
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
        $sql = "SELECT * FROM compra ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        //echo $sql;
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    while ($row = $this->Registro()){
                        $obj= new Compra();
                        $obj->setIdcompra($row['idcompra']);
                        //$obj->getObjusuario()->setIdusuario($row['idusuario']);
                        $obj->cargar();

                        //$usuario=new Usuario();
                        //$usuario->setIdusuario($row['idusuario']);
                        //$usuario->cargar();
                        //$this->setear($row['idcompra'], $row['cofecha'], $usuario);
                        //$this->setear($row['idcompra'], $row['cofecha'], $row['idusuario']);
                        array_push($arreglo, $obj);
                    }
                }
            } else {
                $this->setmensajeoperacion("Tabla->listar: ".$this->getError());
            }
        }
        //print_r($arreglo);
        return $arreglo;
    }
}

?>