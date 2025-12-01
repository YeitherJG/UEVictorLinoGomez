<?php
include '../conexion.php';

$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$nacimiento = $_POST['nacimiento'];
$genero = $_POST['genero'];
$id_representante = $_POST['id_representante'];

$sql = "INSERT INTO estudiantes (nombre, apellido, fecha_nacimiento, genero, id_representante)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssi", $nombre, $apellido, $nacimiento, $genero, $id_representante);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Estudiante agregado correctamente#estudiantes");
} else {
    echo "Error al agregar estudiante: " . $conexion->error;
}
?>
