<?php
include 'conexion.php';

$id_estudiante = $_GET['id_estudiante'];

$sql_est = "SELECT nombre, apellido FROM estudiantes WHERE id_estudiante = ?";
$stmt_est = $conexion->prepare($sql_est);
$stmt_est->bind_param("i", $id_estudiante);
$stmt_est->execute();
$result_est = $stmt_est->get_result();
$est = $result_est->fetch_assoc();

echo "<h2>Historial de Matrícula de {$est['nombre']} {$est['apellido']}</h2>";

$sql = "SELECT m.*, g.nombre_grado, r.nombre AS nombre_representante, r.apellido AS apellido_representante
        FROM matriculas m
        JOIN grados_niveles g ON m.id_grado = g.id_grado
        JOIN representantes r ON m.id_representante = r.id_representante
        WHERE m.id_estudiante = ?
        ORDER BY m.fecha_matricula DESC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_estudiante);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
  echo "<div class='grid-matriculas'>";
  while ($fila = $resultado->fetch_assoc()) {
    echo "<div class='card'>
            <div class='card-header'>
              <h4>{$fila['anio_escolar']}</h4>
            </div>
            <div class='card-body'>
              <p><strong>Grado:</strong> {$fila['nombre_grado']}</p>
              <p><strong>Sección:</strong> {$fila['seccion']}</p>
              <p><strong>Turno:</strong> {$fila['turno']}</p>
              <p><strong>Fecha de matrícula:</strong> {$fila['fecha_matricula']}</p>
              <p><strong>Representante:</strong> {$fila['nombre_representante']} {$fila['apellido_representante']}</p>
            </div>
          </div>";
  }
  echo "</div>";
} else {
  echo "<p>No hay matrículas registradas para este estudiante.</p>";
}
?>
