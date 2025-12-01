<?php
require('../fpdf/fpdf.php');
include '../conexion.php';

// Consulta de estudiantes por género
$sql = "SELECT genero, COUNT(*) AS total
        FROM estudiantes
        WHERE genero IS NOT NULL AND genero != ''
        GROUP BY genero";
$result = $conexion->query($sql);

// Verificar si hay resultados
if ($result->num_rows === 0) {
    echo "<script>
            alert('No hay estudiantes registrados con género definido.');
            window.history.back();
          </script>";
    exit;
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte de Estudiantes por Genero',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,10,'Genero',1);
$pdf->Cell(60,10,'Total Estudiantes',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
while($row = $result->fetch_assoc()) {
    $pdf->Cell(60,10,utf8_decode($row['genero']),1);
    $pdf->Cell(60,10,$row['total'],1);
    $pdf->Ln();
}

$pdf->Output('D','estudiantes_por_genero.pdf'); // 'D' fuerza descarga
?>
