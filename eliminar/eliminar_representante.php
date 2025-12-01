<?php
include '../conexion.php';

$id = $_POST['id_representante'];

$sql = "DELETE FROM representantes WHERE id_representante=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Representante eliminado correctamente#representantes");
} else {
    echo "Error al eliminar representante: " . $conexion->error;
}
?>
