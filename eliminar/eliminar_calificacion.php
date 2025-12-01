<?php
include '../conexion.php';

if (isset($_POST['id_calificacion'])) {
  $id_calificacion = $_POST['id_calificacion'];

  $stmt = $conexion->prepare("DELETE FROM calificaciones WHERE id_calificacion = ?");
  $stmt->bind_param("i", $id_calificacion);

  if ($stmt->execute()) {
    header("Location: ../index.php#calificacion");
    exit;
  } else {
    echo "Error al eliminar la calificación: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo "Error: No se recibió el ID de la calificación.";
}
?>
