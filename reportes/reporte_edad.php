<?php
require('../fpdf/fpdf.php');
include '../conexion.php';

// Consulta de estudiantes por edades
$sql = "SELECT 
            id_estudiante,
            nombre,
            apellido,
            fecha_nacimiento,
            TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad
        FROM estudiantes
        WHERE fecha_nacimiento IS NOT NULL AND fecha_nacimiento != ''
        ORDER BY edad;";
$result = $conexion->query($sql);

// Verificar si hay resultados
if ($result->num_rows === 0) {
    echo "<script>
            alert('No hay estudiantes registrados.');
            window.history.back();
          </script>";
    exit;
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte de Estudiantes por Edad',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(30,10,'ID',1);
$pdf->Cell(80,10,'Nombre y Apellido',1);
$pdf->Cell(30,10,'Edad',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
while($row = $result->fetch_assoc()) {
    $pdf->Cell(30,10,$row['id_estudiante'],1);
    $pdf->Cell(80,10,utf8_decode($row['nombre'].' '.$row['apellido']),1);
    $pdf->Cell(30,10,utf8_decode($row['edad'].' años'),1);
    $pdf->Ln();
}


$pdf->Output('D','estudiantes_por_edades.pdf'); // 'D' fuerza descarga
?>
