<?php
include 'conexion.php';

// Paginación
$registrosPorPagina = 12;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Búsqueda
$buscar = isset($_GET['buscar_representante']) ? $conexion->real_escape_string($_GET['buscar_representante']) : '';
$sql_where = '';
if (!empty($buscar)) {
    $sql_where = " WHERE nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR id_representante LIKE '%$buscar%' ";
}

// Total de registros
$sql_total = "SELECT COUNT(*) FROM representantes $sql_where";
$resultado_total = $conexion->query($sql_total);
$totalRegistros = $resultado_total ? $resultado_total->fetch_row()[0] : 0;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal
$sql = "SELECT * FROM representantes $sql_where ORDER BY apellido ASC LIMIT $inicio, $registrosPorPagina";
$resultado = $conexion->query($sql);
?>

<div class="section-heading">
  <h2>Representantes</h2>
</div>

<div class="search-container">
  <form method="GET" action="#representantes">
    <input type="text" name="buscar_representante" placeholder="Buscar por nombre, apellido o cédula" value="<?php echo htmlspecialchars($buscar); ?>" required>
    <button type="submit" class="button-action">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="index.php#representantes" class="button-action">Limpiar</a>
    <?php endif; ?>
  </form>
</div>

<div class="actions-container">
  <button onclick="abrirModal('crudRepresentanteModal')" class="button-action">Agregar Representante</button>
</div>

<div class="grid-estudiantes">
  <?php if ($resultado && $resultado->num_rows > 0): ?>
    <?php while($fila = $resultado->fetch_assoc()): ?>
      <div class="student-card">
        <div class="card-header">
          <h4><?php echo htmlspecialchars($fila["nombre"]) . " " . htmlspecialchars($fila["apellido"]); ?></h4>
        </div>
        <div class="card-body">
          <p><strong>Cédula:</strong> <?php echo $fila["id_representante"]; ?></p>
          <p><strong>Teléfono:</strong> <?php echo $fila["telefono"]; ?></p>
          <p><strong>Email:</strong> <?php echo $fila["email"]; ?></p>
          <p><strong>Parentesco:</strong> <?php echo $fila["parentesco"]; ?></p>

          <!-- Botones flotantes -->
          <button class="editar-representantes-btn"
            onclick="abrirModal('modalEditarRepresentante'); 
              document.getElementById('edit_id').value='<?php echo $fila['id_representante']; ?>';
              document.getElementById('edit_nombre').value='<?php echo $fila['nombre']; ?>';
              document.getElementById('edit_apellido').value='<?php echo $fila['apellido']; ?>';
              document.getElementById('edit_telefono').value='<?php echo $fila['telefono']; ?>';
              document.getElementById('edit_email').value='<?php echo $fila['email']; ?>';
              document.getElementById('edit_parentesco').value='<?php echo $fila['parentesco']; ?>';">
            ✏️
          </button>

          <form method="POST" action="eliminar/eliminar_representante.php" onsubmit="return confirmarEliminarR();">
            <input type="hidden" name="id_representante" value="<?php echo $fila['id_representante']; ?>">
            <button type="submit" class="eliminar-representantes-btn">🗑️</button>
          </form>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-data-card">No se encontraron representantes.</div>
  <?php endif; ?>
</div>

<?php if ($totalPaginas > 1): ?>
  <div class="paginacion text-center">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="<?php if ($i === $paginaActual) echo 'active'; ?>">
          <a href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($buscar); ?>#representantes"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
    <p>Total de registros: <?php echo $totalRegistros; ?></p>
  </div>
<?php endif; ?>

<!-- MODAL AGREGAR REPRESENTANTE -->
<div id="crudRepresentanteModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Agregar Representante</h3>
    <form method="POST" action="agregar/agregar_representante.php">
      <input type="number" name="id_representante" placeholder="ID interno" required>
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <input type="text" name="telefono" placeholder="Teléfono" required>
      <input type="email" name="email" placeholder="Correo electrónico" required>
      <input type="text" name="parentesco" placeholder="Parentesco (Padre, Madre, Tutor...)" required>
      <button type="submit" class="button-action">Guardar</button>
    </form>
  </div>
</div>

<!-- MODAL EDITAR REPRESENTANTE -->
<div id="modalEditarRepresentante" class="modal">
  <div class="modal-content">
    <span class="close-editar">&times;</span>
    <h3>Editar Representante</h3>
    <form method="POST" action="editar/editar_representante.php">
      <input type="hidden" name="id_representante" id="edit_id">
      <input type="text" name="nombre" id="edit_nombre" required>
      <input type="text" name="apellido" id="edit_apellido" required>
      <input type="text" name="telefono" id="edit_telefono" required>
      <input type="email" name="email" id="edit_email" required>
      <input type="text" name="parentesco" id="edit_parentesco" required>
      <button type="submit" class="button-action">Actualizar</button>
    </form>
  </div>
</div>
