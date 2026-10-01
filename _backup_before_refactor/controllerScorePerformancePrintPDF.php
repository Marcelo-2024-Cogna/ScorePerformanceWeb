<?php
// Inclui a biblioteca FPDF
//require('config/fpdf/fpdf.php');
//class PDF extends FPDF {

// Cria a classe PDF estendendo a classe base FPDF
require_once('config/tcpdf/tcpdf.php');
class PDF extends TCPDF{

    /**
     * @method Cabeçalho do documento
     * @version 1.0.1
     * */ 
    public function Header() 
    {
        // Definir o título
        $label = mb_convert_encoding('Score Performance - Impressão', 'UTF-8', 'auto');
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, $label, 0, 0, 'C');
        $this->Ln(10); // Linha em branco
    }

    /**
     * @method Rodapé do documento
     * @version 1.0.1
     * */     
    public function Footer()
    {
        // Posição a 1,5 cm do final
        $this->SetY(-15);
        // Fonte Arial itálico 8
        $this->SetFont('helvetica', 'I', 8);
        // Número de página
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }
}
?>
