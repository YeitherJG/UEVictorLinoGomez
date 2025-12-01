<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id_matricula'];
  $sql = "DELETE FROM matriculas WHERE id_matricula = ?";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=matricula eliminada correctamente#matricula");
  } else {
    echo "Error al eliminar: " . $stmt->error;
  }
}
?>
 