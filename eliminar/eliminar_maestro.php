<?php
include '../conexion.php';

$id = $_POST['id_maestro'];

$sql = "DELETE FROM maestros WHERE id_maestro=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Maestro eliminado correctamente#maestros");
} else {
    echo "Error al eliminar maestro: " . $conexion->error;
}
?>
