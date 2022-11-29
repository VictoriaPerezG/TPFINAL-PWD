<?php 
class Usuario extends BaseDatos{
    private $idusuario;
    private $usnombre;
    private $uspass;
    private $usmail;
    private $usdeshabilitado;
    private $mensajeoperacion;
    
    public function __construct(){
        parent::__construct();
        $this->idusuario="";
        $this->usnombre="";
        $this->uspass="";
        $this->usmail= "";
        $this->usdeshabilitado="";
        $this->mensajeoperacion = "";
    }

    public function setear($idusuario, $usnombre, $uspass, $usmail, $usdeshabilitado){
        $this->setIdusuario($idusuario);
        $this->setUsnombre($usnombre);
        $this->setUspass($uspass);
        $this->setUsmail($usmail);
        /*if($usdeshabilitado = '0000-00-00 00:00:00')
             $usdeshabilitado = "null";*/
        $this->setUsdeshabilitado($usdeshabilitado);
    }

    public function getIdusuario(){
        return $this->idusuario;
    }

    public function setIdusuario($idusuario){
        $this->idusuario = $idusuario;
    }
    
    public function getUsnombre(){
        return $this->usnombre; 
    }
    public function setUsnombre($usnombre){
        $this->usnombre = $usnombre; 
    }

    public function getUspass(){
        return $this->uspass;  
    }
    public function setUspass($uspass){
        $this->uspass = $uspass;   
    }

    public function getUsmail(){
        return $this->usmail; 
    }
    public function setUsmail($usmail){
        $this->usmail = $usmail;
    }

    public function getUsdeshabilitado(){
        return $this->usdeshabilitado; 
    }
    public function setUsdeshabilitado($usdeshabilitado){
        $this->usdeshabilitado = $usdeshabilitado;
    }

    public function getmensajeoperacion(){
        return $this->mensajeoperacion;
        
    }
    public function setmensajeoperacion($valor){
        $this->mensajeoperacion = $valor;  
    }


    public function Buscar($idusuario){
        $resp = false;
        $sql="SELECT * FROM usuario WHERE idusuario=".$idusuario;
        if($this->Iniciar()){
            if($this->Ejecutar($sql)){
                if($row = $this->Registro()){
                    $this->setear($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
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
        $sql="SELECT * FROM usuario WHERE idusuario = ".$this->getIdusuario();
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $this->Registro();
                    $this->setear($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
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
        $sql="INSERT INTO usuario(usnombre, uspass, usmail, usdeshabilitado) VALUES('".strtoupper($this->getUsnombre())."', '".$this->getUspass()."', '".$this->getUsmail()."', '".$this->getUsdeshabilitado()."');";

        if ($this->Iniciar()) {
            if ($elid = $this->Ejecutar($sql)) {
                $this->setIdusuario($elid);
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
        $sql="UPDATE usuario SET usnombre='".$this->getUsnombre()."',uspass='".$this->getUspass()."',usmail='".$this->getUsmail()."', usdeshabilitado='".$this->getUsdeshabilitado()."'".
        " WHERE idusuario=".$this->getIdusuario();
        
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
        $sql="DELETE FROM usuario WHERE idusuario=".$this->getIdusuario();
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
        $sql="SELECT * FROM usuario ";
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        if ($this->Iniciar()) {
            $res = $this->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    while ($row = $this->Registro()){
                        $usuario= new Usuario();
                        $usuario->setIdusuario($row['idusuario']);
                        $usuario->cargar(); 
                        array_push($arreglo, $usuario);
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