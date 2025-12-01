<?php
include '../conexion.php';

$id = $_POST['id_representante'];
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$parentesco = $_POST['parentesco'];

$sql = "UPDATE representantes SET nombre=?, apellido=?, telefono=?, email=?, parentesco=? WHERE id_representante=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssi", $nombre, $apellido, $telefono, $email, $parentesco, $id);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Representante actualizado correctamente#representantes");
} else {
    echo "Error al actualizar representante: " . $conexion->error;
}
?>
