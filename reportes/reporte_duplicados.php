<?php
require('../fpdf/fpdf.php');
include '../conexion.php';

$sql = "SELECT m.id_estudiante, e.nombre, e.apellido, m.anio_escolar, COUNT(*) AS repeticiones
        FROM matriculas m
        JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
        GROUP BY m.id_estudiante, m.anio_escolar
        HAVING COUNT(*) > 1";
$result = $conexion->query($sql);

if ($result->num_rows === 0) {
    // Mostrar alert y regresar a la página anterior
    echo "<script>
            alert('No hay matrículas duplicadas registradas.');
            window.history.back();
          </script>";
    exit;
}

// Crear PDF solo si hay duplicados
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte de Matriculas Duplicadas',0,1,'C');
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(60,10,'Estudiante',1);
$pdf->Cell(40,10,'Año Escolar',1);
$pdf->Cell(40,10,'Repeticiones',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
while($row = $result->fetch_assoc()) {
    $pdf->Cell(60,10,$row['apellido'].' '.$row['nombre'],1);
    $pdf->Cell(40,10,$row['anio_escolar'],1);
    $pdf->Cell(40,10,$row['repeticiones'],1);
    $pdf->Ln();
}

$pdf->Output('D','matriculas_duplicadas.pdf'); // 'D' fuerza descarga
?>
