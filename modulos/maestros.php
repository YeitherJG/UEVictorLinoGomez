<?php
include 'conexion.php';

// Paginación
$registrosPorPagina = 12;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Búsqueda
$buscar = isset($_GET['buscar_maestro']) ? $conexion->real_escape_string($_GET['buscar_maestro']) : '';
$sql_where = '';
if (!empty($buscar)) {
    $sql_where = " WHERE nombre LIKE '%$buscar%' OR apellido LIKE '%$buscar%' OR cedula_maestro LIKE '%$buscar%' OR nombre LIKE '%$buscar%' ";
}

// Total de registros
$sql_total = "SELECT COUNT(*) FROM maestros $sql_where";
$resultado_total = $conexion->query($sql_total);
$totalRegistros = $resultado_total ? $resultado_total->fetch_row()[0] : 0;
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Consulta principal
$sql = "SELECT m.*, g.nombre_grado, g.turno, g.seccion
        FROM maestros m
        LEFT JOIN grados_niveles g ON m.id_grado = g.id_grado
        $sql_where
        ORDER BY m.apellido ASC
        LIMIT $inicio, $registrosPorPagina";

$resultado = $conexion->query($sql);
?>

<div class="section-heading">
  <h2>Maestros</h2>
</div>

<div class="search-container">
  <form method="GET" action="#maestros">
    <input type="text" name="buscar_maestro" placeholder="Buscar por nombre, apellido o cédula" value="<?php echo htmlspecialchars($buscar); ?>" required>
    <button type="submit" class="button-action">Buscar</button>
    <?php if (!empty($buscar)): ?>
      <a href="index.php#maestros" class="button-action">Limpiar</a>
    <?php endif; ?>
  </form>
</div>

<div class="actions-container">
  <button onclick="abrirModal('crudMaestroModal')" class="button-action">Agregar Maestro</button>
</div>

<div class="grid-maestros">
  <?php if ($resultado && $resultado->num_rows > 0): ?>
    <?php while($fila = $resultado->fetch_assoc()): ?>
 <div class="card shadow-sm mb-4">
  <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
    <h5 class="mb-0"><?php echo $fila["nombre"] . " " . $fila["apellido"]; ?></h5>
    <span class="badge <?php echo ($fila["condicion_laboral"] === "Activo") ? 'bg-success' : 'bg-danger'; ?>">
      <?php echo $fila["condicion_laboral"]; ?>
    </span>
  </div>

  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <p class="mb-1"><i class="bi bi-person-badge"></i> <strong>Cédula:</strong> <?php echo $fila["cedula_maestro"]; ?></p>
        <p class="mb-1"><i class="bi bi-telephone"></i> <strong>Teléfono:</strong> <?php echo $fila["telefono"]; ?></p>
        <p class="mb-1"><i class="bi bi-envelope"></i> <strong>Email:</strong> <?php echo $fila["email"]; ?></p>
      </div>
      <div class="col-md-6">
        <p class="mb-1"><i class="bi bi-calendar"></i> <strong>Nacimiento:</strong> <?php echo $fila["fecha_nacimiento"]; ?></p>
        <p class="mb-1"><i class="bi bi-gender-ambiguous"></i> <strong>Género:</strong> <?php echo $fila["genero"]; ?></p>
        <p class="mb-1"><i class="bi bi-house"></i> <strong>Dirección:</strong> <?php echo $fila["direccion"]; ?></p>
        <p class="mb-1"><i class="bi bi-mortarboard"></i><strong>Grado asignado:</strong> <?php echo $fila["nombre_grado"]; ?> (<?php echo $fila["turno"]; ?>-<?php echo $fila["seccion"]; ?>)</p>
      </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
      <button class="btn btn-sm btn-outline-primary"
        onclick="abrirModal('modalEditarMaestro');
          document.getElementById('maestro_id').value='<?php echo $fila['id_maestro']; ?>';
          document.getElementById('maestro_cedula').value='<?php echo $fila['cedula_maestro']; ?>';
          document.getElementById('maestro_nombre').value='<?php echo $fila['nombre']; ?>';
          document.getElementById('maestro_apellido').value='<?php echo $fila['apellido']; ?>';
          document.getElementById('maestro_fecha').value='<?php echo $fila['fecha_nacimiento']; ?>';
          document.getElementById('maestro_genero').value='<?php echo $fila['genero']; ?>';
          document.getElementById('maestro_direccion').value='<?php echo $fila['direccion']; ?>';
          document.getElementById('maestro_telefono').value='<?php echo $fila['telefono']; ?>';
          document.getElementById('maestro_email').value='<?php echo $fila['email']; ?>';
          document.getElementById('maestro_condicion').value='<?php echo $fila['condicion_laboral']; ?>';
          document.getElementById('maestro_grado').value='<?php echo $fila['id_grado']; ?>';
          document.getElementById('maestro_turno').value='<?php echo $fila['turno']; ?>';">
          
        <i class="bi bi-pencil"></i> Editar
      </button>

      <form method="POST" action="eliminar/eliminar_maestro.php" onsubmit="return confirmarEliminarM();">
        <input type="hidden" name="id_maestro" value="<?php echo $fila['id_maestro']; ?>">
        <button type="submit" class="btn btn-sm btn-outline-danger">
          <i class="bi bi-trash"></i> Eliminar
        </button>
      </form>
    </div>
  </div>
</div>




    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-data-card">No se encontraron maestros.</div>
  <?php endif; ?>
</div>
<?php if ($totalPaginas > 1): ?>
  <div class="paginacion text-center">
    <ul class="pagination">
      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <li class="<?php if ($i === $paginaActual) echo 'active'; ?>">
          <a href="?pagina=<?php echo $i; ?>&buscar_maestro=<?php echo urlencode($buscar); ?>#maestros">
            <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>
    </ul>
    <p>Total de registros: <?php echo $totalRegistros; ?></p>
  </div>
<?php endif; ?>

<div id="crudMaestroModal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h3>Agregar Maestro</h3>
    <form method="POST" action="agregar/agregar_maestro.php">
      <input type="text" name="cedula_maestro" placeholder="Cédula" required>
      <input type="text" name="nombre" placeholder="Nombre" required>
      <input type="text" name="apellido" placeholder="Apellido" required>
      <input type="date" name="fecha_nacimiento" required>
      <select name="genero" required>
        <option value="">Género</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
      </select>
      <input type="text" name="direccion" placeholder="Dirección" required>
      <input type="text" name="telefono" placeholder="Teléfono" required>
      <input type="email" name="email" placeholder="Correo electrónico" required>
      <select name="condicion_laboral" required>
        <option value="">Condición laboral</option>
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
      </select>


      <!-- Selector de grado -->
      <select name="id_grado" required>
        <option value="">Seleccione grado</option>
        <?php
          // Traemos grado, turno y sección
          $grados = $conexion->query("SELECT id_grado, nombre_grado, turno, seccion 
                                      FROM grados_niveles 
                                      ORDER BY nombre_grado, turno, seccion");
          while ($g = $grados->fetch_assoc()) {
            echo "<option value='{$g['id_grado']}'>
                    {$g['nombre_grado']} ({$g['turno']}) - Sección {$g['seccion']}
                  </option>";
          }
        ?>
      </select>


      <button type="submit" class="button-action">Guardar</button>
    </form>
  </div>
</div>
<div id="modalEditarMaestro" class="modal">
  <div class="modal-content">
    <span class="close-editar">&times;</span>
    <h3>Editar Maestro</h3>
    <form method="POST" action="editar/editar_maestro.php">
      <input type="hidden" name="id_maestro" id="maestro_id">
      <input type="text" name="cedula_maestro" id="maestro_cedula" required>
      <input type="text" name="nombre" id="maestro_nombre" required>
      <input type="text" name="apellido" id="maestro_apellido" required>
      <input type="date" name="fecha_nacimiento" id="maestro_fecha" required>
      <select name="genero" id="maestro_genero" required>
        <option value="">Género</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
      </select>
      <input type="text" name="direccion" id="maestro_direccion" required>
      <input type="text" name="telefono" id="maestro_telefono" required>
      <input type="email" name="email" id="maestro_email" required>
      <select name="condicion_laboral" required>
        <option value="">Condición laboral</option>
        <option value="Activo">Activo</option>
        <option value="Inactivo">Inactivo</option>
      </select>


      <!-- Selector de grado -->
      <select name="id_grado" required>
        <option value="">Seleccione grado</option>
        <?php
          // Traemos grado, turno y sección
          $grados = $conexion->query("SELECT id_grado, nombre_grado, turno, seccion 
                                      FROM grados_niveles 
                                      ORDER BY nombre_grado, turno, seccion");
          while ($g = $grados->fetch_assoc()) {
            echo "<option value='{$g['id_grado']}'>
                    {$g['nombre_grado']} ({$g['turno']}) - Sección {$g['seccion']}
                  </option>";
          }
        ?>
      </select>

      <button type="submit" class="button-action">Actualizar</button>
    </form>
  </div>
</div>

