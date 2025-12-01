<?php
include 'conexion.php';

// Cargar estudiantes en arreglo (para reutilizar en todos los selects)
$estudiantes_rs = $conexion->query("SELECT id_estudiante, nombre, apellido FROM estudiantes ORDER BY nombre, apellido");
$estudiantes = [];
if ($estudiantes_rs) {
  while ($e = $estudiantes_rs->fetch_assoc()) {
    $estudiantes[] = $e;
  }
}

// Cargar grados con turno para mostrar nombre legible
$grados_rs = $conexion->query("SELECT id_grado, nombre_grado, turno FROM grados_niveles ORDER BY nombre_grado");
$grados = [];
if ($grados_rs) {
  while ($g = $grados_rs->fetch_assoc()) {
    $grados[] = $g;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Eventos del Estudiante</title>
  <style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    h2 { margin-bottom: 10px; }
    .panel { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; }
    .card { border: 1px solid #ccc; padding: 15px; border-radius: 8px; background-color: #f9f9f9; }
    .card h3 { margin-top: 0; }
    label { font-weight: bold; margin-top: 8px; display: block; }
    input, select, button { display: block; margin: 6px 0 12px; padding: 8px; width: 100%; }
    button { background-color: #007bff; color: white; border: none; cursor: pointer; }
    button:hover { background-color: #0056b3; }
    .hint { color: #555; font-size: 12px; }
  </style>
</head>
<body>

<h2>🎓 Panel de Eventos Académicos</h2>

<div class="panel">

  <!-- Retiro -->
  <div class="card">
    <h3>📤 Registrar Retiro</h3>
    <form method="POST" action="modulos/retirar_estudiante.php">
      <label>Estudiante</label>
      <select name="id_estudiante" required>
        <option value="">Seleccione estudiante</option>
        <?php foreach ($estudiantes as $e): ?>
          <option value="<?php echo $e['id_estudiante']; ?>">
            <?php echo htmlspecialchars($e['nombre'].' '.$e['apellido']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Grado</label>
      <select name="id_grado" required>
        <option value="">Seleccione grado</option>
        <?php foreach ($grados as $g): ?>
          <option value="<?php echo $g['id_grado']; ?>">
            <?php echo htmlspecialchars($g['nombre_grado'].' ('.$g['turno'].')'); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Sección</label>
      <input type="text" name="seccion" placeholder="Ej.: A, B, C" required>

      <label>Año escolar</label>
      <input type="text" name="anio_escolar" placeholder="Ej.: 2025-2026" required>

      <button type="submit">Registrar Retiro</button>
      <p class="hint">Nota: El retiro marcará al estudiante como inactivo en el período seleccionado sin borrar sus datos.</p>
    </form>
  </div>

  <!-- Reingreso -->
  <div class="card">
    <h3>📥 Registrar Reingreso</h3>
    <form method="POST" action="reingresar_estudiante.php">
      <label>Estudiante</label>
      <select name="id_estudiante" required>
        <option value="">Seleccione estudiante</option>
        <?php foreach ($estudiantes as $e): ?>
          <option value="<?php echo $e['id_estudiante']; ?>">
            <?php echo htmlspecialchars($e['nombre'].' '.$e['apellido']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Grado</label>
      <select name="id_grado" required>
        <option value="">Seleccione grado</option>
        <?php foreach ($grados as $g): ?>
          <option value="<?php echo $g['id_grado']; ?>">
            <?php echo htmlspecialchars($g['nombre_grado'].' ('.$g['turno'].')'); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Sección</label>
      <input type="text" name="seccion" placeholder="Ej.: A, B, C" required>

      <label>Año escolar</label>
      <input type="text" name="anio_escolar" placeholder="Ej.: 2025-2026" required>

      <button type="submit">Registrar Reingreso</button>
      <p class="hint">Tip: Si el reingreso es a otro grado o turno, asegúrate de actualizar matrícula y sección correctamente.</p>
    </form>
  </div>

  <!-- Egreso -->
  <div class="card">
    <h3>🎓 Registrar Egreso</h3>
    <form method="POST" action="egresar_estudiante.php">
      <label>Estudiante</label>
      <select name="id_estudiante" required>
        <option value="">Seleccione estudiante</option>
        <?php foreach ($estudiantes as $e): ?>
          <option value="<?php echo $e['id_estudiante']; ?>">
            <?php echo htmlspecialchars($e['nombre'].' '.$e['apellido']); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Grado final</label>
      <select name="id_grado" required>
        <option value="">Seleccione grado</option>
        <?php foreach ($grados as $g): ?>
          <option value="<?php echo $g['id_grado']; ?>">
            <?php echo htmlspecialchars($g['nombre_grado'].' ('.$g['turno'].')'); ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label>Año escolar</label>
      <input type="text" name="anio_escolar" placeholder="Ej.: 2025-2026" required>

      <button type="submit">Registrar Egreso</button>
      <p class="hint">El egreso cierra el ciclo académico del estudiante en el grado seleccionado.</p>
    </form>
  </div>

  <!-- Promoción automática -->
  <div class="card">
    <h3>📈 Promoción Automática</h3>
    <form method="POST" action="promocionar_estudiantes.php">
      <button type="submit">Ejecutar promoción para estudiantes con promedio ≥ 10</button>
      <p class="hint">Esta acción crea la matrícula del siguiente grado para quienes cumplan el criterio.</p>
    </form>
  </div>

</div>

</body>
</html>
