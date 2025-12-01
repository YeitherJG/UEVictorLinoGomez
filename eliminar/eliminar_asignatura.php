<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST['id_asignatura'] ?? '';

  if ($id === '') {
    header("Location: ../index.php?mensaje=id_vacio#asignaturas");
    exit;
  }

  $sql = "DELETE FROM asignaturas WHERE id_asignatura = ?";
  $stmt = $conexion->prepare($sql);

  if (!$stmt) {
    header("Location: ../index.php?mensaje=error_preparacion#asignaturas");
    exit;
  }

  $stmt->bind_param("i", $id);

  if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=asignatura_eliminada#asignaturas");
    exit;
  } else {
    header("Location: ../index.php?mensaje=error_eliminar_asignatura#asignaturas");
    exit;
  }
}
?>
