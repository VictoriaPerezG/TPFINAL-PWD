<?php 
class Rol extends BaseDatos{
    private $idrol;
    private $rodescripcion;
    private $mensajeoperacion;
    
    public function __construct(){
        parent::__construct();
        $this->idrol="";
        $this->rodescripcion="";
        $this->mensajeoperacion = "";
    }

    public function setear($idrol, $rodescripcion){
        $this->setIdrol($idrol);
        $this->setRodescripcion($rodescripcion);
    }

    public function getIdrol(){
        return $this->idrol;
    }

    public function setIdrol($idrol){
        $this->idrol = $idrol;
    }
    
    public function getRodescripcion(){
        return $this->rodescripcion; 
    }
    public function setRodescripcion($rodescripcion){
        $this->rodescripcion = $rodescripcion; 
    }

    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
        
    }
    public function setmensajeoperacion($valor){
        $this->mensajeoperacion = $valor;  
    }


    public function Buscar($idrol){
        $resp = false;
        $sql="SELECT * FROM rol WHERE idrol=".$idrol;
        if($this->Iniciar()){
            if($this->Ejecutar($sql)){
                if($row = $this->Registro()){
                    $this->setear($row['idrol'], $row['rodescripcion']);
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
        $sql="SELECT * FROM rol WHERE idrol = ".$this->getIdrol();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $this->setear($row['idrol'], $row['rodescripcion']);
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
        $sql="INSERT INTO rol(rodescripcion) VALUES('".strtoupper($this->getRodescripcion())."');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdrol($elid);
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
        $sql="UPDATE rol SET rodescripcion='".$this->getRodescripcion()."'".
        " WHERE idrol=".$this->getIdrol();
        
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
        $sql="DELETE FROM rol WHERE idrol=".$this->getIdrol();
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
        $sql="SELECT * FROM rol ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    while ($row = $this->Registro()){
                        $rol= new Rol();
                        $rol->setIdrol($row['idrol']);
                        $rol->cargar(); 
                        array_push($arreglo, $rol);
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