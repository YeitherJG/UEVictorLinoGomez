<div class="section-heading">
  <h2>Calificaciones</h2>
</div>

<div class="actions-container">
  <button onclick="abrirModal('crudCalificacionModal')" class="button-action">Agregar Calificación</button>
</div>

<div class="grid-estudiantes">
  <?php
    // Paginación
    $registrosPorPagina = 12;
    $paginaActual = isset($_GET['pagina_calificaciones']) ? (int)$_GET['pagina_calificaciones'] : 1;
    if ($paginaActual < 1) $paginaActual = 1;
    $inicio = ($paginaActual - 1) * $registrosPorPagina;

    // Total de registros
    $sql_total = "SELECT COUNT(*) AS total FROM calificaciones";
    $resultado_total = $conexion->query($sql_total);
    $totalRegistros = $resultado_total ? (int)$resultado_total->fetch_assoc()['total'] : 0;
    $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

    // Consulta principal con límite
    $sql = "SELECT c.*, 
               CONCAT(COALESCE(e.apellido,''),' ',COALESCE(e.nombre,'')) AS estudiante,
               a.nombre_asignatura,
               g.nombre_grado,
               g.turno,
               g.seccion
            FROM calificaciones c
            LEFT JOIN estudiantes e ON c.id_estudiante = e.id_estudiante
            LEFT JOIN asignaturas a ON c.id_asignatura = a.id_asignatura
            LEFT JOIN grados_niveles g ON c.id_grado = g.id_grado
            ORDER BY c.fecha_registro DESC
            LIMIT $inicio, $registrosPorPagina";

    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
      while ($fila = $resultado->fetch_assoc()) {
        echo "<div class='student-card'>
                <div class='card-header'>
                <h4>" . (!empty($fila['estudiante']) ? htmlspecialchars($fila['estudiante']) : 'Sin registro') . "</h4> </div>
                <p><strong>Asignatura:</strong> " . (!empty($fila['nombre_asignatura']) ? htmlspecialchars($fila['nombre_asignatura']) : '-') . "</p>
                <p><strong>Grado:</strong> " . (!empty($fila['nombre_grado']) ? htmlspecialchars($fila['nombre_grado']) : '-') . "</p>
                <p><strong>Turno:</strong> " . (!empty($fila['turno']) ? htmlspecialchars($fila['turno']) : '-') . "</p>
                <p><strong>Sección:</strong> " . (!empty($fila['seccion']) ? htmlspecialchars($fila['seccion']) : '-') . "</p>
                <p><strong>Lapso:</strong> {$fila['lapso']}</p>
                <p><strong>Nota:</strong> {$fila['nota']}</p>
                <p><strong>Observación:</strong> " . (!empty($fila['observacion']) ? htmlspecialchars($fila['observacion']) : '-') . "</p>
                <p><strong>Fecha:</strong> {$fila['fecha_registro']}</p>

                <div class='card-actions'>
                  <button class='editar-calificacion-btn'
                    data-id='{$fila['id_calificacion']}'
                    data-estudiante='{$fila['id_estudiante']}'
                    data-asignatura='{$fila['id_asignatura']}'
                    data-grado='{$fila['id_grado']}'
                    data-lapso='{$fila['lapso']}'
                    data-nota='{$fila['nota']}'
                    data-observacion='" . htmlspecialchars($fila['observacion'], ENT_QUOTES) . "'
                    data-fecha='{$fila['fecha_registro']}'>
                    ✏️
                  </button>

                  <form method='POST' action='eliminar/eliminar_calificacion.php' onsubmit='return confirm(\"¿Eliminar calificación?\");'>
                    <input type='hidden' name='id_calificacion' value='{$fila['id_calificacion']}'>
                    <button type='submit' class='eliminar-calificacion-btn'>🗑️</button>
                  </form>
                  </div>
              </div>";
      }
    } else {
      echo "<div class='no-data-card'>No hay calificaciones registradas.</div>";
    }
  ?>
</div>

<?php if ($totalPaginas > 1): ?>
  <div class="paginacion text-center">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="<?php if ($i === $paginaActual) echo 'active'; ?>">
          <a href="index.php?pagina_calificacion=<?php echo $i; ?>#calificacion"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
    <p>Total de registros: <?php echo $totalRegistros; ?></p>
  </div>
<?php endif; ?>


<!-- Modales iguales a los tuyos --><!-- Modal Agregar -->
<div id="crudCalificacionModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('crudCalificacionModal')">&times;</span>
    <h3>Agregar Calificación</h3>
    <form method="POST" action="agregar/agregar_calificaciones.php">
      
      <!-- Select Estudiante -->
      <select name="id_estudiante" required>
        <option value="">Seleccione estudiante</option>
        <?php
          $estudiantes = $conexion->query("SELECT id_estudiante, nombre, apellido FROM estudiantes ORDER BY apellido");
          while ($e = $estudiantes->fetch_assoc()) {
            echo "<option value='{$e['id_estudiante']}'>{$e['apellido']} {$e['nombre']}</option>";
          }
        ?>
      </select>

      <!-- Select Asignatura -->
      <select name="id_asignatura" required>
        <option value="">Seleccione asignatura</option>
        <?php
          $asignaturas = $conexion->query("SELECT id_asignatura, nombre_asignatura FROM asignaturas ORDER BY nombre_asignatura");
          while ($a = $asignaturas->fetch_assoc()) {
            echo "<option value='{$a['id_asignatura']}'>{$a['nombre_asignatura']}</option>";
          }
        ?>
      </select>

      <!-- Select Grado con Turno y Sección -->
      <select name="id_grado" required>
        <option value="">Seleccione grado</option>
        <?php
          $grados = $conexion->query("SELECT id_grado, nombre_grado, turno, seccion FROM grados_niveles ORDER BY nombre_grado");
          while ($g = $grados->fetch_assoc()) {
            echo "<option value='{$g['id_grado']}'>{$g['nombre_grado']} - {$g['turno']} - {$g['seccion']}</option>";
          }
        ?>
      </select>

      <!-- Lapso -->
      <select name="lapso" required>
        <option value="">Lapso</option>
        <option value="1">1er Lapso</option>
        <option value="2">2do Lapso</option>
        <option value="3">3er Lapso</option>
      </select>

      <input type="number" name="nota" placeholder="Nota (0-20)" min="0" max="20" required>
      <input type="text" name="observacion" placeholder="Observación">
      <input type="date" name="fecha_registro" required>
      <button type="submit" class="button-action">Guardar</button>
    </form>
  </div>
</div>

<!-- Modal Editar -->
<div id="modalEditarCalificacion" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('modalEditarCalificacion')">&times;</span>
    <h3>Editar Calificación</h3>
    <form method="POST" action="editar/editar_calificacion.php">
      <input type="hidden" name="id_calificacion" id="edit_id_calificacion">

      <!-- Select Estudiante -->
      <select name="id_estudiante" id="edit_estudiante" required>
        <option value="">Seleccione estudiante</option>
        <?php
          $estudiantes = $conexion->query("SELECT id_estudiante, nombre, apellido FROM estudiantes ORDER BY apellido");
          while ($e = $estudiantes->fetch_assoc()) {
            echo "<option value='{$e['id_estudiante']}'>{$e['apellido']} {$e['nombre']}</option>";
          }
        ?>
      </select>

      <!-- Select Asignatura -->
      <select name="id_asignatura" id="edit_asignatura" required>
        <option value="">Seleccione asignatura</option>
        <?php
          $asignaturas = $conexion->query("SELECT id_asignatura, nombre_asignatura FROM asignaturas ORDER BY nombre_asignatura");
          while ($a = $asignaturas->fetch_assoc()) {
            echo "<option value='{$a['id_asignatura']}'>{$a['nombre_asignatura']}</option>";
          }
        ?>
      </select>

      <!-- Select Grado con Turno y Sección -->
      <select name="id_grado" id="edit_grado" required>
        <option value="">Seleccione grado</option>
        <?php
          $grados = $conexion->query("SELECT id_grado, nombre_grado, turno, seccion FROM grados_niveles ORDER BY nombre_grado");
          while ($g = $grados->fetch_assoc()) {
            echo "<option value='{$g['id_grado']}'>{$g['nombre_grado']} - {$g['turno']} - {$g['seccion']}</option>";
          }
        ?>
      </select>

      <!-- Lapso -->
      <select name="lapso" id="edit_lapso" required>
        <option value="1">1er Lapso</option>
        <option value="2">2do Lapso</option>
        <option value="3">3er Lapso</option>
      </select>

      <input type="number" name="nota" id="edit_nota" min="0" max="20" required>
      <input type="text" name="observacion" id="edit_observacion">
      <input type="date" name="fecha_registro" id="edit_fecha" required>

      <button type="submit" class="button-action">Actualizar</button>
    </form>
  </div>
</div>

<script>
function abrirModal(id) {
  document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
  document.getElementById(id).style.display = 'block';
}
function cerrarModal(id) {
  document.getElementById(id).style.display = 'none';
}

document.querySelectorAll('.editar-calificacion-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    document.getElementById('edit_id_calificacion').value = this.dataset.id;
    document.getElementById('edit_estudiante').value = this.dataset.estudiante;
    document.getElementById('edit_asignatura').value = this.dataset.asignatura;
    document.getElementById('edit_grado').value = this.dataset.grado;
    document.getElementById('edit_lapso').value = this.dataset.lapso;
    document.getElementById('edit_nota').value = this.dataset.nota;
    document.getElementById('edit_observacion').value = this.dataset.observacion || '';
    document.getElementById('edit_fecha').value = this.dataset.fecha;

    abrirModal('modalEditarCalificacion');
  });
});
</script>
