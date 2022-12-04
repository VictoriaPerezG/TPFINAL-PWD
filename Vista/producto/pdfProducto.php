<?php
include_once "../../configuracion.php";
$objAbmProducto = new AbmProducto();
$datos = data_submitted(); //Devuelve un array = Array([idProducto] => X)
//verEstructura($datos);

$objProducto=NULL;
if (isset($datos['idproducto'])){ //Si existe un objeto con idProducto, obtengo el objeto Producto con todos sus datos
    $listaProducto = $objAbmProducto->buscar($datos);
    //verEstructura($listaTabla);
    //if (count($listaAuto)==1){
    $objProducto= $listaProducto[0];

 	include('plantillaProducto.php');
	//Instaciamos la clase para genrear el documento pdf
	$pdf=new PDF();
	$pdf->AliasNbPages();

	//Agregamos la primera pagina al documento pdf
	//pdf->AddPage('O'); //orientacion horizontal

	$pdf->AddPage(); 
	//$pdf->SetFillColor(200,232,232);
	$pdf->SetTitle('Detalles del auto');
	if($objProducto->getProestado()=="1"){
		$estado="HABILITADO";
	}
	else{
		$estado="INHABILITADO";
	}
	
	$pdf->Ln(10);
	$pdf->SetFont('Arial','',14);
	$pdf->SetTextColor(5,5,5);
	$pdf->Write(5, 'Id producto: '.$objProducto->getIdproducto());
	$pdf->Ln(10);
	$pdf->Write(5, 'Nombre: '.utf8_decode($objProducto->getPronombre()));
	$pdf->Ln(10);
	$pdf->Write(5, 'Precio: '."$".$objProducto->getProprecio());
	$pdf->Ln(10);
	$pdf->Write(5, 'Stock: '.$objProducto->getProcantstock());
	$pdf->Ln(10);
	$pdf->Write(5, 'Estado: '.utf8_decode($estado));
	$pdf->Ln(10);
	$pdf->Write(5, 'Detalles: '.$objProducto->getProdetalle());
	$pdf->Ln(10);
		
	$directory='../img/';
    $dirint = dir($directory);

    while (($archivo = $dirint->read()) != false){
        if (strpos($archivo,"jpg")){ 
            $foto = $directory.utf8_decode($objProducto->getPronombre()).".jpg";
        }
        if (strpos($archivo,"jpeg")){ 
            $foto = $directory.utf8_decode($objProducto->getPronombre()).".jpeg";
        }
        if (strpos($archivo,"png")){ 
            $foto = $directory.utf8_decode($objProducto->getPronombre()).".png";
        }
        if (strpos($archivo,"JPG")){ 
            $foto = $directory.utf8_decode($objProducto->getPronombre()).".JPG";
        }
        if (strpos($archivo,"JPEG")){ 
            $foto = $directory.utf8_decode($objProducto->getPronombre()).".JPEG";
        }
        if (strpos($archivo,"PNG")){ 
            $foto = $directory.utf8_decode($objProducto->getPronombre()).".PNG";
        }

    }

    //$pdf->Image($foto, 40, 140, 130, 90);
    $pdf->SetLineWidth(0.1);
    $pdf->SetDrawColor(0);
    $pdf->ClippingRoundedRect(40,140,135,95,5,true);
	$pdf->Image($foto,42,142,130,90);
	//$pdf->Image($foto,"distancia en blanco de izq a der","distancia de arriba hacia abajo donde arrnaca a mostrarse",130,90);
	$pdf->UnsetClipping();	
	$pdf->Output();
}

?>