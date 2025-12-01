<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id     = $_POST['id_asignaturas'] ?? '';
  $nombre = trim($_POST['nombre_asignatura'] ?? '');

  if ($id === '' || $nombre === '') {
    header("Location: ../index.php?mensaje=datos_incompletos#asignaturas");
    exit;
  }

  $sql = "UPDATE asignaturas SET nombre_asignatura = ? WHERE id_asignatura = ?";
  $stmt = $conexion->prepare($sql);

  if (!$stmt) {
    header("Location: ../index.php?mensaje=error_preparacion#asignaturas");
    exit;
  }

  $stmt->bind_param("si", $nombre, $id);

  if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=asignatura_editada#asignaturas");
    exit;
  } else {
    header("Location: ../index.php?mensaje=error_editar_asignatura#asignaturas");
    exit;
  }
}
?>
