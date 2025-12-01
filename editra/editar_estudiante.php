<?php
include '../conexion.php';

$id_estudiante = $_POST['id_estudiante'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$fecha_nacimiento = $_POST['nacimiento'];
$genero = $_POST['genero'];
$id_representante = $_POST['id_representante'];

$sql = "UPDATE estudiantes SET nombre=?, apellido=?, fecha_nacimiento=?, genero=?, id_representante=? WHERE id_estudiante=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssii", $nombre, $apellido, $fecha_nacimiento, $genero, $id_representante, $id_estudiante);


if ($stmt->execute()) {
  header("Location: ../index.php?mensaje=Estudiante actualizado correctamente#estudiantes");
} else {
  echo "Error al ejecutar la consulta: " . $stmt->error;
}
?>
