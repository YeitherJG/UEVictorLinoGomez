<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nombre = trim($_POST['nombre_asignatura'] ?? '');

  if ($nombre === '') {
    header("Location: ../index.php?mensaje=nombre_asignatura_vacio#asignaturas");
    exit;
  }

  $sql = "INSERT INTO asignaturas (nombre_asignatura) VALUES (?)";
  $stmt = $conexion->prepare($sql);

  if (!$stmt) {
    header("Location: ../index.php?mensaje=error_preparacion#asignaturas");
    exit;
  }

  $stmt->bind_param("s", $nombre);

  if ($stmt->execute()) {
    header("Location: ../index.php?mensaje=asignatura_agregada#asignaturas");
    exit;
  } else {
    header("Location: ../index.php?mensaje=error_agregar_asignatura#asignaturas");
    exit;
  }
}
?>
