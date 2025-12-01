<?php
include '../conexion.php';

$id = $_POST['id_grado'];

$sql = "DELETE FROM grados_niveles WHERE id_grado=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Grado eliminado correctamente#grados");
} else {
    echo "Error al eliminar grado: " . $conexion->error;
}
?>
