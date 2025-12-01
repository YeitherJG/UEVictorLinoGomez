<?php
include '../conexion.php';

$id = $_POST['id_representante'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$parentesco = $_POST['parentesco'];

$sql = "INSERT INTO representantes (id_representante, nombre, apellido, telefono, email, parentesco)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("isssss", $id, $nombre, $apellido, $telefono, $email, $parentesco);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Representante agregado correctamente#representantes");
} else {
    echo "Error al agregar representante: " . $conexion->error;
}
?>
