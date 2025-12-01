<?php
require('../fpdf/fpdf.php');
include '../conexion.php';

// Consulta distribución por turno
$sql = "SELECT turno, COUNT(*) AS total
        FROM grados_niveles
        GROUP BY turno";
$result = $conexion->query($sql);

// Verificar si hay resultados
if ($result->num_rows === 0) {
    echo "<script>
            alert('No hay matrículas registradas.');
            window.history.back();
          </script>";
    exit;
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte de Distribucion por Turno',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,10,'Turno',1);
$pdf->Cell(60,10,'Total Alumnos',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
while($row = $result->fetch_assoc()) {
    $pdf->Cell(60,10,ucfirst($row['turno']),1);
    $pdf->Cell(60,10,$row['total'],1);
    $pdf->Ln();
}

$pdf->Output('D','distribucion_turno.pdf'); // 'D' fuerza descarga
?>
