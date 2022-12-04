<?php
require('../fpdf/fpdf.php');
	
class PDF extends FPDF{
		
	function Header(){
		$this->Image('../img/logo.png', 5, 5, 25);
		$this->SetFont('Helvetica','BU',20);
		$this->SetTextColor(28, 68, 158);
		$this->Cell(30);
		$this->Cell(150,30, 'DETALLES DEL PRODUCTO',0,0,'C');
		$this->Ln(20);
	}
		
	function Footer(){
		$this->SetY(-20);
		$this->SetFont('Arial','I', 12);
		$this->Cell(0,10, 'Pagina '.$this->PageNo().'/{nb}',0,0,'C' );
		$this->Ln(5);
		$this->Cell(0,10, utf8_decode('Grupo N°3 - Victoria Perez Gonzalez - Patricio Rubio - Sergio Valicenti'),0,0,'C');
	}



	function ClippingRoundedRect($x, $y, $w, $h, $r, $outline=false){
        $k = $this->k;
        $hp = $this->h;
        $op=$outline ? 'S' : 'n';
        $MyArc = 4/3 * (sqrt(2) - 1);

        $this->_out(sprintf('q %.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out(' W '.$op);
    }		


    function _Arc($x1, $y1, $x2, $y2, $x3, $y3){
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }


    function UnsetClipping(){
        $this->_out('Q');
    }
}
?>