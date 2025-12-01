<?php
include 'conexion.php';

// Paginación
$registrosPorPagina = 12;
$paginaActual = isset($_GET['pagina_asignaturas']) ? (int)$_GET['pagina_asignaturas'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Búsqueda
$buscar = isset($_GET['buscar_asignatura']) ? $conexion->real_escape_string($_GET['buscar_asignatura']) : '';
$sql_where = '';
if (!empty($buscar)) {
  $sql_where = "WHERE nombre_asignatura LIKE '%$buscar%'";
}

// Total de registros
$sql_total = "SELECT COUNT(*) FROM asignaturas $sql_where";
$resultado_total = $conexion->query($sql_total);
$totalRegistros = $resultado_total ? $resultado_total->fetch_row()[0] : 0;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal con LIMIT
$sql = "SELECT * FROM asignaturas $sql_where 
        ORDER BY nombre_asignatura ASC 
        LIMIT $inicio, $registrosPorPagina";
$resultado = $conexion->query($sql);
?>

<div id="asignaturas" class="seccion">
  <h2>ASIGNATURAS</h2>
</div>

<!-- Barra de búsqueda -->
<div class="search-container">
  <form method="GET" action="asignatura.php#asignaturas">
    <input type="text" name="buscar_asignatura" placeholder="Buscar asignatura" 
           value="<?php echo htmlspecialchars($buscar); ?>" required>
    <button type="submit" class="button-action">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="asignatura.php#asignaturas" class="button-action">Limpiar</a>
    <?php endif; ?>
  </form>
</div>

<!-- Botón para abrir modal -->
<div class="actions-container">
  <button onclick="abrirModal('modalAgregarAsignatura')" class="button-action">Agregar Asignatura</button>
</div>

<!-- Listado de asignaturas -->
<div class="grid-estudiantes">
  <?php
    if ($resultado && $resultado->num_rows > 0) {
      while ($fila = $resultado->fetch_assoc()) {
        ?>
        <div class="student-card">
          <div class="card-header">
            <h4><?php echo htmlspecialchars($fila['nombre_asignatura']); ?></h4>
          </div>
          <div class="card-body">
            <button type="button" class="editar-asignatura-btn"
              data-id="<?php echo $fila['id_asignatura']; ?>"
              data-nombre="<?php echo htmlspecialchars($fila['nombre_asignatura'], ENT_QUOTES); ?>">
              ✏️
            </button>

            <form method="POST" action="eliminar/eliminar_asignatura.php" 
                  onsubmit="return confirm('¿Eliminar asignatura?');">
              <input type="hidden" name="id_asignatura" value="<?php echo $fila['id_asignatura']; ?>">
              <button type="submit" class="eliminar-asignatura-btn">🗑️</button>
            </form>
          </div>
        </div>
        <?php
      }
    } else {
      echo "<p>No hay asignaturas registradas.</p>";
    }
  ?>
</div>

<!-- Paginación -->
<?php if ($totalPaginas > 1): ?>
  <div class="paginacion text-center">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="<?php if ($i === $paginaActual) echo 'active'; ?>">
          <a href="?pagina_asignaturas=<?php echo $i; ?>&buscar_asignatura=<?php echo urlencode($buscar); ?>#asignaturas">
            <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>
    </ul>
    <p>Total de asignaturas: <?php echo $totalRegistros; ?></p>
  </div>
<?php endif; ?>

<!-- Modal AGREGAR ASIGNATURA -->
<div id="modalAgregarAsignatura" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('modalAgregarAsignatura')">&times;</span>
    <h3>Agregar Asignatura</h3>
    <form method="POST" action="agregar/agregar_asignatura.php">
      <input type="text" name="nombre_asignatura" placeholder="Nombre de la asignatura" required>
      <button type="submit" class="button-action">Guardar</button>
    </form>
  </div>
</div>

<!-- Modal EDITAR ASIGNATURA -->
<div id="modalEditarAsignatura" class="modal">
  <div class="modal-content">
    <span class="close" onclick="cerrarModal('modalEditarAsignatura')">&times;</span>
    <h3>Editar Asignatura</h3>
    <form method="POST" action="editar/editar_asignatura.php">
      <input type="hidden" name="id_asignatura" id="edit_id_asignatura">
      <input type="text" name="nombre_asignatura" id="edit_nombre_asignatura" required>
      <button type="submit" class="button-action">Guardar Cambios</button>
    </form>
  </div>
</div>

<script>
// Abrir modal por ID
function abrirModal(id) {
  document.querySelectorAll('.modal').forEach(m => m.style.display = 'none');
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'block';
}

// Cerrar modal por ID
function cerrarModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'none';
}

// Cerrar modal al hacer clic fuera
window.addEventListener('click', function (e) {
  document.querySelectorAll('.modal').forEach(modal => {
    if (e.target === modal) modal.style.display = 'none';
  });
});

// Cargar datos en el modal de edición
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.editar-asignatura-btn');
  if (!btn) return;
  document.getElementById('edit_id_asignatura').value = btn.dataset.id;
  document.getElementById('edit_nombre_asignatura').value = btn.dataset.nombre;
  abrirModal('modalEditarAsignatura');
});
</script>
