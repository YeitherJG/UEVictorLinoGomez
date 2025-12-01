<?php
include '../conexion.php';

$id = $_POST['id_estudiante'];

$sql = "DELETE FROM estudiantes WHERE id_estudiante=?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=Estudiante eliminado correctamente#estudiantes");
} else {
    echo "Error al eliminar estudiante: " . $conexion->error;
}
?>
