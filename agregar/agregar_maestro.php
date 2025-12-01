<?php
include '../conexion.php';

$cedula = $_POST['cedula_maestro'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$fecha = $_POST['fecha_nacimiento'];
$genero = $_POST['genero'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$condicion = $_POST['condicion_laboral'];
$id_grado = $_POST['id_grado'];

$sql = "INSERT INTO maestros (cedula_maestro, nombre, apellido, fecha_nacimiento, genero, direccion, telefono, email, condicion_laboral, id_grado)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
  die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("issssssssi", $cedula, $nombre, $apellido, $fecha, $genero, $direccion, $telefono, $email, $condicion, $id_grado);

if ($stmt->execute()) {
  header("Location: ../index.php?mensaje=Maestro agregado correctamente#maestros");
} else {
  echo "Error al agregar maestro: " . $stmt->error;
}
?>
