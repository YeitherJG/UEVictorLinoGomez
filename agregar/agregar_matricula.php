<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Recibir datos del formulario
  $id_estudiante = $_POST['id_estudiante'];
  $id_grado = $_POST['id_grado'];
  $id_representante = $_POST['id_representante'];
  $anio_escolar = $_POST['anio_escolar'];
  $fecha_matricula = $_POST['fecha_matricula'];

  // Validar que no exista matrícula duplicada para el mismo estudiante y año escolar
  $verificar = $conexion->prepare("SELECT id_matricula FROM matriculas WHERE id_estudiante = ? AND anio_escolar = ?");
  $verificar->bind_param("is", $id_estudiante, $anio_escolar);
  $verificar->execute();
  $verificar->store_result();

  if ($verificar->num_rows > 0) {
    echo "Ya existe una matrícula registrada para este estudiante en el año escolar seleccionado.";
    exit;
  }

  // Insertar nueva matrícula
  $sql = "INSERT INTO matriculas (id_estudiante, id_grado, id_representante, anio_escolar, fecha_matricula)
          VALUES (?, ?, ?, ?, ?)";
  $stmt = $conexion->prepare($sql);
  $stmt->bind_param("iiiss", $id_estudiante, $id_grado, $id_representante, $anio_escolar, $fecha_matricula);

 // Después de insertar la matrícula exitosamente
if ($stmt->execute()) {
  // Insertar evento en historial
  $comentario = "Matriculado en el año escolar $anio_escolar, grado $id_grado";
  $insert_historial = $conexion->prepare("INSERT INTO historial_estudiante 
    (id_estudiante, estado, fecha_cambio, comentarios) 
    VALUES (?, 'Matriculado', CURDATE(), ?)");
  $insert_historial->bind_param("is", $id_estudiante, $comentario);
  $insert_historial->execute();

    header("Location: ../index.php?mensaje=matricula agregada correctamente#matricula");
    exit;
  } else {
    echo "Error al registrar matrícula: " . $stmt->error;
  }
}
