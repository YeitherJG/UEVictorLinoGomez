<?php
require('../fpdf/fpdf.php');
include '../conexion.php';

// Consulta de estudiantes sin matrícula
$sql = "SELECT e.id_estudiante, e.nombre, e.apellido
        FROM estudiantes e
        LEFT JOIN matriculas m ON e.id_estudiante = m.id_estudiante
        WHERE m.id_estudiante IS NULL";
$result = $conexion->query($sql);

// Verificar si hay resultados
if ($result->num_rows === 0) {
    echo "<script>
            alert('Todos los estudiantes tienen matrícula registrada.');
            window.history.back();
          </script>";
    exit;
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte de Estudiantes sin Matricula',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,'Nombre',1);
$pdf->Cell(80,10,'Apellido',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
while($row = $result->fetch_assoc()) {
    $pdf->Cell(80,10,utf8_decode($row['nombre']),1);
    $pdf->Cell(80,10,utf8_decode($row['apellido']),1);
    $pdf->Ln();
}

$pdf->Output('D','estudiantes_sin_matricula.pdf'); // 'D' fuerza descarga
?>
