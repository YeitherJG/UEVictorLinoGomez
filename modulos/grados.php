<?php
include 'conexion.php';

// Paginación
$registrosPorPagina = 12;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Búsqueda
$buscar = isset($_GET['buscar_grado']) ? $conexion->real_escape_string($_GET['buscar_grado']) : '';
$sql_where = '';
if (!empty($buscar)) {
    $sql_where = " WHERE nombre_grado LIKE '%$buscar%' OR seccion LIKE '%$buscar%' ";
}

// Total de registros
$sql_total = "SELECT COUNT(*) FROM grados_niveles $sql_where";
$resultado_total = $conexion->query($sql_total);
$totalRegistros = $resultado_total ? $resultado_total->fetch_row()[0] : 0;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal
$sql = "SELECT * FROM grados_niveles $sql_where ORDER BY nombre_grado ASC LIMIT $inicio, $registrosPorPagina";
$resultado = $conexion->query($sql);
?>

<div class="section-heading">
  <h2>Grados</h2>
</div>

<div class="search-container">
  <form method="GET" action="#grados">
    <input type="text" name="buscar_grado" placeholder="Buscar por grado o sección" value="<?php echo htmlspecialchars($buscar); ?>" required>
    <button type="submit" class="button-action">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="index.php#grados" class="button-action">Limpiar</a>
    <?php endif; ?>
  </form>
</div>

<div class="actions-container">
  <button onclick="abrirModal('crudGradoModal')" class="button-action">Agregar Grado</button>
</div>

<div class="grid-estudiantes">
  <?php if ($resultado && $resultado->num_rows > 0): ?>
    <?php while($fila = $resultado->fetch_assoc()): ?>
      <div class="student-card">
        <div class="card-header">
          <h4><?php echo htmlspecialchars($fila["nombre_grado"]); ?> - Sección <?php echo htmlspecialchars($fila["seccion"]); ?></h4>
        </div>
        <div class="card-body">
          <p><strong>Turno:</strong> <?php echo $fila["turno"]; ?></p>

          <!-- Botones flotantes -->
        <button class="editar-grados-btn"
            onclick="abrirModal('modalEditarGrado'); 
              document.getElementById('gra_nombre').value='<?php echo $fila['nombre_grado']; ?>';
              document.getElementById('gra_seccion').value='<?php echo $fila['seccion']; ?>';
              document.getElementById('gra_turno').value='<?php echo $fila['turno']; ?>';">
            ✏️
          </button>

         <form method="POST" action="eliminar/eliminar_grado.php" onsubmit="return confirmarEliminarG();">
            <input type="hidden" name="id_grado" value="<?php echo $fila['id_grado']; ?>">
            <button type="submit" class="eliminar-grados-btn">🗑️</button>
        </form>

        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-data-card">No se encontraron grados.</div>
  <?php endif; ?>
</div>

<?php if ($totalPaginas > 1): ?>
  <div class="paginacion text-center">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="<?php if ($i === $paginaActual) echo 'active'; ?>">
          <a href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($buscar); ?>#grados"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
    <p>Total de registros: <?php echo $totalRegistros; ?></p>
  </div>
<?php endif; ?>

<!-- MODAL AGREGAR GRADO -->
<div id="crudGradoModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Agregar Grado</h3>
    <form method="POST" action="agregar/agregar_grado.php">
      <input type="text" name="nombre_grado" placeholder="Nombre del grado" required>
      <input type="text" name="seccion" placeholder="Sección" required>
      <select name="turno" required>
        <option value="">Turno</option>
        <option value="Matutino">Matutino</option>
        <option value="Vespertino">Vespertino</option>
      </select>
      <button type="submit" class="button-action">Guardar</button>
    </form>
  </div>
</div>

<!-- MODAL EDITAR GRADO -->
<div id="modalEditarGrado" class="modal">
  <div class="modal-content">
    <span class="close-editar">&times;</span>
    <h3>Editar Grado</h3>
    <form method="POST" action="editar/editar_grado.php">
      <input type="text" name="nombre_grado" id="gra_nombre" placeholder="Nombre del grado" required>
      <input type="text" name="seccion" id="gra_seccion" placeholder="Sección" required>
      <select name="turno" id="gra_turno" required>
        <option value="">Turno</option>
        <option value="Matutino">Matutino</option>
        <option value="Vespertino">Vespertino</option>
      </select>
      <button type="submit" class="button-action">Actualizar</button>
    </form>
  </div>
</div>
