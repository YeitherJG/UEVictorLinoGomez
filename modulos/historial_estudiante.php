<?php
include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial del Estudiante</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    h2 {
      margin-bottom: 10px;
    }
    form {
      margin-bottom: 20px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
      margin-bottom: 30px;
    }
    th, td {
      border: 1px solid #ccc;
      padding: 6px 10px;
      text-align: center;
    }
    th {
      background-color: #f2f2f2;
    }
    .anio-header {
      background-color: #e0e0e0;
      font-weight: bold;
      text-align: left;
      padding: 8px;
    }
  </style>
</head>
<body>

<h2>📚 Historial Académico del Estudiante</h2>

<form method="GET" action="">
  <label for="id_estudiante">Seleccione estudiante:</label>
  <select name="id_estudiante" required>
    <option value="">-- Seleccione --</option>
    <?php
    $estudiantes = $conexion->query("SELECT id_estudiante, nombre, apellido FROM estudiantes ORDER BY nombre, apellido");
    while ($e = $estudiantes->fetch_assoc()) {
      $selected = (isset($_GET['id_estudiante']) && $_GET['id_estudiante'] == $e['id_estudiante']) ? 'selected' : '';
      echo "<option value='{$e['id_estudiante']}' $selected>{$e['nombre']} {$e['apellido']}</option>";
    }
    ?>
  </select>
  <button type="submit">Ver historial</button>
</form>

<?php
if (isset($_GET['id_estudiante'])) {
  $id_estudiante = $_GET['id_estudiante'];

  $sql = "SELECT 
            m.anio_escolar,
            g.nombre_grado,
            g.seccion,
            g.turno,
            r.nombre AS representante,
            a.nombre_asignatura,
            c.lapso,
            c.nota,
            c.observacion
          FROM matriculas m
          JOIN grados_niveles g ON m.id_grado = g.id_grado
          JOIN representantes r ON m.id_representante = r.id_representante
          LEFT JOIN calificaciones c 
                 ON m.id_estudiante = c.id_estudiante 
                AND m.id_grado = c.id_grado
          LEFT JOIN asignaturas a ON c.id_asignatura = a.id_asignatura    
          WHERE m.id_estudiante = ?
          ORDER BY m.anio_escolar, g.nombre_grado, c.lapso, a.nombre_asignatura";

  $stmt = $conexion->prepare($sql);
  if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
  }
  $stmt->bind_param("i", $id_estudiante);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows > 0) {
    $historial = [];
    $tieneCalificaciones = false; // bandera
  
    while ($fila = $resultado->fetch_assoc()) {
      $anio = $fila['anio_escolar'];
      $clave = $anio . ' - ' . $fila['nombre_grado'] . ' ' . $fila['seccion'];
  
      if (!isset($historial[$clave])) {
        $historial[$clave] = [
          'turno' => $fila['turno'],
          'representante' => $fila['representante'],
          'calificaciones' => []
        ];
      }
  
      if (!empty($fila['nombre_asignatura'])) {
        $historial[$clave]['calificaciones'][] = [
          'asignatura' => $fila['nombre_asignatura'],
          'lapso' => $fila['lapso'],
          'nota' => $fila['nota'],
          'observacion' => $fila['observacion']
        ];
        $tieneCalificaciones = true;
      }
    }
  
    if ($tieneCalificaciones) {
      foreach ($historial as $periodo => $datos) {
        echo "<div class='anio-header'>📅 $periodo | Turno: {$datos['turno']} | Representante: {$datos['representante']}</div>";
        echo "<table>
                <tr>
                  <th>Asignatura</th>
                  <th>Lapso</th>
                  <th>Nota</th>
                  <th>Observación</th>
                </tr>";
  
        foreach ($datos['calificaciones'] as $nota) {
          echo "<tr>
                  <td>{$nota['asignatura']}</td>
                  <td>{$nota['lapso']}</td>
                  <td>{$nota['nota']}</td>
                  <td>" . (!empty($nota['observacion']) ? $nota['observacion'] : '-') . "</td>
                </tr>";
        }
  
        echo "</table>";
      }
    } else {
      echo "<p>El estudiante no tiene calificaciones registradas en su historial académico.</p>";
    }
  } else {
    echo "<p>No se encontró historial académico para este estudiante.</p>";
  }
}
?>
<script>
  // Evita que al recargar se posicione en otra sección
  if (window.location.hash === "#calificaciones") {
    window.location.hash = ""; 
  }
</script>


</body>
</html>