<?php
include '../conexion.php';

$nombre = $_POST['nombre_grado'];
$seccion = $_POST['seccion'];
$turno = $_POST['turno'];

$sql = "INSERT INTO grados_niveles (nombre_grado, seccion, turno) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $seccion, $turno);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Grado agregado correctamente#grados");
} else {
    echo "Error al agregar grado: " . $conexion->error;
}
?>
