<?php
include 'conexion.php';

// Paginación
$registrosPorPagina = 12;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Búsqueda
$buscar = isset($_GET['buscar_estudiante']) ? $conexion->real_escape_string($_GET['buscar_estudiante']) : '';
$sql_where = '';
if (!empty($buscar)) {
  $sql_where = " WHERE e.nombre LIKE '%$buscar%' 
                 OR e.apellido LIKE '%$buscar%' 
                 OR e.id_estudiante LIKE '%$buscar%' 
                 OR e.genero LIKE '%$buscar%' 
                 OR e.fecha_nacimiento LIKE '%$buscar%'";
}


// Total de registros
$sql_total = "SELECT COUNT(*) FROM estudiantes $sql_where";
$resultado_total = $conexion->query($sql_total);
$totalRegistros = $resultado_total ? $resultado_total->fetch_row()[0] : 0;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal
$sql = "SELECT e.*, r.nombre AS nombre_representante
        FROM estudiantes e
        LEFT JOIN representantes r ON e.id_representante = r.id_representante
        $sql_where
        ORDER BY e.apellido ASC
        LIMIT $inicio, $registrosPorPagina";

$resultado = $conexion->query($sql);
?>

<div class="section-heading">
  <h2>Estudiantes</h2>
</div>

<div class="search-container">
  <form method="GET" action="#estudiantes">
    <input type="text" name="buscar_estudiante" placeholder="Buscar por nombre, apellido o ID" value="<?php echo htmlspecialchars($buscar); ?>" required>
    <button type="submit" class="button-action">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="index.php#estudiantes" class="button-action">Limpiar</a>
    <?php endif; ?>
  </form>
</div>

<div class="actions-container">
  <button onclick="abrirModal('crudEstudianteModal')" class="button-action">Agregar Estudiante</button>
</div>

<div class="grid-estudiantes">
  <?php if ($resultado && $resultado->num_rows > 0): ?>
    <?php while($fila = $resultado->fetch_assoc()): ?>
      <div class="student-card">
        <div class="card-header">
          <h4><?php echo htmlspecialchars($fila["nombre"]) . " " . htmlspecialchars($fila["apellido"]); ?></h4>
        </div>
        <div class="card-body">
          <p><strong>Representate:</strong> <?php echo $fila["nombre_representante"]; ?></p>
          <p><strong>Género:</strong> <?php echo $fila["genero"]; ?></p>
          <p><strong>Nacimiento:</strong> <?php echo $fila["fecha_nacimiento"]; ?></p>
        

          <!-- Botones flotantes -->
          <button class="editar-estudiantes-btn"
              onclick="abrirModal('modalEditarEstudiante'); 
              document.getElementById('alum_id').value='<?php echo $fila['id_estudiante']; ?>'; 
              document.getElementById('alum_nombre').value='<?php echo htmlspecialchars($fila['nombre'], ENT_QUOTES); ?>'; 
              document.getElementById('alum_apellido').value='<?php echo htmlspecialchars($fila['apellido'], ENT_QUOTES); ?>'; 
              document.getElementById('alum_nacimiento').value='<?php echo $fila['fecha_nacimiento']; ?>'; 
              document.getElementById('alum_genero').value='<?php echo $fila['genero']; ?>'; 
              document.getElementById('alum_representante').value='<?php echo $fila['id_representante']; ?>';">
            ✏️
          </button>


          <form method="POST" action="eliminar/eliminar_estudiante.php" onsubmit="return confirmarEliminar();">
            <input type="hidden" name="id_estudiante" value="<?php echo $fila['id_estudiante']; ?>">
            <button type="submit" class="eliminar-estudiantes-btn">🗑️</button>
          </form>
        </div>
    </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-data-card">No se encontraron estudiantes.</div>
  <?php endif; ?>
</div>

<?php if ($totalPaginas > 1): ?>
  <div class="paginacion text-center">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="<?php if ($i === $paginaActual) echo 'active'; ?>">
          <a href="?pagina=<?php echo $i; ?>&buscar=<?php echo urlencode($buscar); ?>#estudiantes"><?php echo $i; ?></a>
        </li>
      <?php endfor; ?>
    </ul>
    <p>Total de registros: <?php echo $totalRegistros; ?></p>
  </div>
<?php endif; ?>

<!-- MODALES FUERA DEL BUCLE -->

<!-- Modal Agregar -->
<!-- MODAL AGREGAR ESTUDIANTE -->
<div id="crudEstudianteModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Agregar Estudiante</h3>
    <form method="POST" action="agregar/agregar_estudiante.php">
      <input type="text" name="nombre" placeholder="Nombre completo" required>
      <input type="text" name="apellido" placeholder="Apellido completo" required>
      <input type="date" name="nacimiento" required>
      <select name="genero" required>
        <option value="">Seleccione género</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
      </select>
      <!-- Selector de representante -->
      <select name="id_representante" required>
        <option value="">Seleccione representante</option>
        <?php
          $reps = $conexion->query("SELECT id_representante, nombre FROM representantes");
          while ($rep = $reps->fetch_assoc()) {
            echo "<option value='{$rep['id_representante']}'>{$rep['nombre']}</option>";
          }
        ?>
      </select>

      <button type="submit" class="button-action">Guardar</button>
    </form>
  </div>
</div>

<!-- MODAL EDITAR ESTUDIANTE -->
<div id="modalEditarEstudiante" class="modal">
  <div class="modal-content">
    <span class="close-editar">&times;</span>
    <h3>Editar Estudiante</h3>
    <form method="POST" action="editar/editar_estudiante.php">
      <input type="hidden" name="id_estudiante" id="alum_id">
      <input type="text" name="nombre" id="alum_nombre" placeholder="Nombre completo" required>
      <input type="text" name="apellido" id="alum_apellido" placeholder="Apellido completo" required>
      <input type="date" name="nacimiento" id="alum_nacimiento" required>

      <select name="genero" id="alum_genero" required>
        <option value="">Seleccione género</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
      </select>

      <select name="id_representante" id="alum_representante" required>
        <option value="">Seleccione representante</option>
        <?php
          $reps = $conexion->query("SELECT id_representante, nombre FROM representantes");
          while ($rep = $reps->fetch_assoc()) {
            echo "<option value='{$rep['id_representante']}'>{$rep['nombre']}</option>";
          }
        ?>
      </select>

      <button type="submit" class="button-action">Actualizar</button>
    </form>
  </div>
</div>

          
