<?php 
include 'conexion.php'; 

// Paginación
$registrosPorPagina = 12;
$paginaActual = isset($_GET['pagina_matriculas']) ? (int)$_GET['pagina_matriculas'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Búsqueda
$buscar = isset($_GET['buscar_matricula']) ? $conexion->real_escape_string($_GET['buscar_matricula']) : '';
$sql_where = '';
if (!empty($buscar)) {
    $sql_where = " WHERE e.nombre LIKE '%$buscar%' 
                   OR e.apellido LIKE '%$buscar%' 
                   OR m.id_matricula LIKE '%$buscar%' ";
}

// Total de registros
$sql_total = "SELECT COUNT(*) 
              FROM matriculas m
              JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
              JOIN representantes r ON m.id_representante = r.id_representante
              JOIN grados_niveles g ON m.id_grado = g.id_grado
              $sql_where";
$resultado_total = $conexion->query($sql_total);
$totalRegistros = $resultado_total ? $resultado_total->fetch_row()[0] : 0;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal con LIMIT
$sql = "SELECT m.*, 
               e.nombre AS nombre_estudiante, e.apellido AS apellido_estudiante,
               r.nombre AS nombre_representante, r.apellido AS apellido_representante,
               g.nombre_grado, g.turno, g.seccion
        FROM matriculas m
        JOIN estudiantes e ON m.id_estudiante = e.id_estudiante
        JOIN representantes r ON m.id_representante = r.id_representante
        JOIN grados_niveles g ON m.id_grado = g.id_grado
        $sql_where
        ORDER BY m.fecha_matricula DESC
        LIMIT $inicio, $registrosPorPagina";
$resultado = $conexion->query($sql);
?>

<div id="matriculas" class="seccion">
  <h2>MATRÍCULAS</h2>
</div>

<!-- Barra de búsqueda -->
<div class="search-container">
  <form method="GET" action="index.php#matriculas">
    <input type="text" name="buscar_matricula" placeholder="Buscar por nombre, apellido o ID" 
           value="<?php echo htmlspecialchars($buscar); ?>" required>
    <button type="submit" class="button-action">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="index.php#matriculas" class="button-action">Limpiar</a>
    <?php endif; ?>
  </form>
</div>

<!-- Botón para abrir modal -->
<div class="actions-container">
  <button onclick="abrirModal('crudMatriculaModal')" class="button-action">Agregar Matrícula</button>
</div>

<hr>

<!-- Listado de matrículas -->
<div class="grid-estudiantes">
  <?php
    if ($resultado && $resultado->num_rows > 0) {
      while ($fila = $resultado->fetch_assoc()) {
        $turno_visible = ($fila['turno'] === "Mañana") ? "Matutino" : "Vespertino";
        ?>
        <div class="student-card">
          <div class="card-header">
            <h4><?php echo $fila['apellido_estudiante'] . " " . $fila['nombre_estudiante']; ?></h4>
          </div>
          <div class="card-body">
            <p><strong>Grado:</strong> <?php echo $fila['nombre_grado'] . " (" . $turno_visible . " - Sección " . $fila['seccion'] . ")"; ?></p>
            <p><strong>Año escolar:</strong> <?php echo $fila['anio_escolar']; ?></p>
            <p><strong>Fecha matrícula:</strong> <?php echo $fila['fecha_matricula']; ?></p>
            <p><strong>Representante:</strong> <?php echo $fila['apellido_representante'] . " " . $fila['nombre_representante']; ?></p>

            <button type="button" class="editar-matricula-btn"
              data-id="<?php echo $fila['id_matricula']; ?>"
              data-estudiante="<?php echo $fila['id_estudiante']; ?>"
              data-grado="<?php echo $fila['id_grado']; ?>"
              data-representante="<?php echo $fila['id_representante']; ?>"
              data-anio="<?php echo htmlspecialchars($fila['anio_escolar'], ENT_QUOTES); ?>"
              data-fecha="<?php echo $fila['fecha_matricula']; ?>">
              ✏️
            </button>

            <form method="POST" action="eliminar/eliminar_matricula.php" onsubmit="return confirm('¿Eliminar matrícula?');">
              <input type="hidden" name="id_matricula" value="<?php echo $fila['id_matricula']; ?>">
              <button type="submit" class="eliminar-matricula-btn">🗑️</button>
            </form>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<p>No hay matrículas registradas.</p>";
    }
  ?>
</div>

<!-- Paginación -->
<?php if ($totalPaginas > 1): ?>
  <div class="paginacion text-center">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="<?php if ($i === $paginaActual) echo 'active'; ?>">
          <a href="?pagina_matriculas=<?php echo $i; ?>&buscar_matricula=<?php echo urlencode($buscar); ?>#matriculas">
            <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?> 
    </ul>
    <p>Total de matrículas: <?php echo $totalRegistros; ?></p>
  </div>
<?php endif; ?>


<!-- Modal AGREGAR MATRÍCULA -->
<div id="crudMatriculaModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('crudMatriculaModal')">&times;</span>
    <h3>Registrar Matrícula</h3>
    <form method="POST" action="agregar/agregar_matricula.php">
      
      <!-- Estudiante -->
      <select name="id_estudiante" required>
        <option value="">Seleccione estudiante</option>
        <?php
          $estudiantes = $conexion->query("SELECT id_estudiante, nombre, apellido FROM estudiantes ORDER BY apellido");
          while ($e = $estudiantes->fetch_assoc()) {
            echo "<option value='{$e['id_estudiante']}'>{$e['apellido']} {$e['nombre']}</option>";
          }
        ?>
      </select>

      <!-- Grado -->
      <select name="id_grado" required>
        <option value="">Seleccione grado</option>
        <?php
          $grados = $conexion->query("SELECT id_grado, nombre_grado, turno, seccion FROM grados_niveles ORDER BY nombre_grado, turno, seccion");
          while ($g = $grados->fetch_assoc()) {
            echo "<option value='{$g['id_grado']}'>
                    {$g['nombre_grado']} ({$g['turno']}) - Sección {$g['seccion']}
                  </option>";
          }
        ?>
      </select>

      <!-- Representante -->
      <select name="id_representante" required>
        <option value="">Seleccione representante</option>
        <?php
          $reps = $conexion->query("SELECT id_representante, nombre, apellido FROM representantes ORDER BY apellido");
          while ($r = $reps->fetch_assoc()) {
            echo "<option value='{$r['id_representante']}'>{$r['apellido']} {$r['nombre']}</option>";
          }
        ?>
      </select>

      <!-- Año escolar y fecha -->
      <input type="text" name="anio_escolar" placeholder="Ej. 2024-2025" required>
      <input type="date" name="fecha_matricula" required>

      <button type="submit" class="button-action">Guardar</button>
    </form>
  </div>
</div>

<!-- Modal EDITAR MATRÍCULA -->
<div id="modalEditarMatricula" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('modalEditarMatricula')">&times;</span>
    <h3>Editar Matrícula</h3>
    <form method="POST" action="editar/editar_matricula.php">
      <input type="hidden" name="id_matricula" id="edit_id_matricula">
      <p><strong>ID Matrícula:</strong> <span id="mostrar_id_matricula" style="color: #007bff;"></span></p>

      <select name="id_estudiante" id="edit_estudiante" required>
        <option value="">Seleccione estudiante</option>
        <?php
          $estudiantes = $conexion->query("SELECT id_estudiante, nombre, apellido FROM estudiantes ORDER BY apellido");
          while ($e = $estudiantes->fetch_assoc()) {
            echo "<option value='{$e['id_estudiante']}'>{$e['apellido']} {$e['nombre']}</option>";
          }
        ?>
      </select>

      <select name="id_grado" id="edit_grado" required>
        <option value="">Seleccione grado</option>
        <?php
          $grados = $conexion->query("SELECT id_grado, nombre_grado, turno, seccion FROM grados_niveles ORDER BY nombre_grado, turno, seccion");
          while ($g = $grados->fetch_assoc()) {
            echo "<option value='{$g['id_grado']}'>{$g['nombre_grado']} ({$g['turno']}) - Sección {$g['seccion']}</option>";
          }
        ?>
      </select>

      <select name="id_representante" id="edit_representante" required>
        <option value="">Seleccione representante</option>
        <?php
          $reps = $conexion->query("SELECT id_representante, nombre, apellido FROM representantes ORDER BY apellido");
          while ($r = $reps->fetch_assoc()) {
            echo "<option value='{$r['id_representante']}'>{$r['apellido']} {$r['nombre']}</option>";
          }
        ?>
      </select>

      <!-- Campos faltantes: año escolar y fecha de matrícula -->
      <input type="text" name="anio_escolar" id="edit_anio" placeholder="Ej. 2024-2025" required>
      <input type="date" name="fecha_matricula" id="edit_fecha" required>

      <button type="submit" class="button-action">Guardar Cambios</button>
    </form>
  </div>
</div>


<script>
// Hacer las funciones globales para que funcionen con onclick en HTML
function abrirModal(id) {
  document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'block';
}

function cerrarModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'none';
}

// Cerrar modal al hacer click fuera del contenido
window.addEventListener('click', function (e) {
  document.querySelectorAll('.modal').forEach(modal => {
    if (e.target === modal) modal.style.display = 'none';
  });
});

document.addEventListener('DOMContentLoaded', function() {
  // Delegación de eventos para capturar cualquier botón futuro
  document.addEventListener('click', function(e) {
    const btn = e.target.closest('.editar-matricula-btn');
    if (!btn) return;

    // Validaciones básicas
    const id = btn.dataset.id || '';
    const estudiante = btn.dataset.estudiante || '';
    const grado = btn.dataset.grado || '';
    const representante = btn.dataset.representante || '';
    const anio = btn.dataset.anio || '';
    const fecha = btn.dataset.fecha || '';

    // Llenar campos del modal
    const idField = document.getElementById('edit_id_matricula');
    const showId = document.getElementById('mostrar_id_matricula');
    const selEst = document.getElementById('edit_estudiante');
    const selGrd = document.getElementById('edit_grado');
    const selRep = document.getElementById('edit_representante');
    const inpAnio = document.getElementById('edit_anio');
    const inpFecha = document.getElementById('edit_fecha');

    if (!idField || !selEst || !selGrd || !selRep) {
      console.warn('Faltan elementos del modal de edición (id, selects). Revisa IDs.');
      return;
    }

    idField.value = id;
    if (showId) showId.textContent = id;

    selEst.value = estudiante;
    selGrd.value = grado;
    selRep.value = representante;

    if (inpAnio)  inpAnio.value = anio;
    if (inpFecha) inpFecha.value = fecha;

    abrirModal('modalEditarMatricula');
  });
});
</script>




