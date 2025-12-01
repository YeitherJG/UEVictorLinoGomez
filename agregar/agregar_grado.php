<?php
include '../conexion.php';

$id = $_POST['id_grado'];
$nombre = $_POST['nombre_grado'];
$seccion = $_POST['seccion'];
$turno = $_POST['turno'];

$sql = "INSERT INTO grados_niveles (id_grado, nombre_grado, seccion, turno)
        VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("isss", $id, $nombre, $seccion, $turno);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Grado agregado correctamente#grados");
} else {
    echo "Error al agregar grado: " . $conexion->error;
}
?>
