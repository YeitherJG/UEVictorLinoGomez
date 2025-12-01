<?php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_estudiante  = $_POST['id_estudiante'];
    $id_asignatura  = $_POST['id_asignatura'];
    $lapso          = $_POST['lapso'];
    $nota           = $_POST['nota'];
    $observacion    = $_POST['observacion'];
    $fecha_registro = $_POST['fecha_registro'];

    // Buscar el grado desde la matrícula más reciente
    $sql = "SELECT id_grado 
            FROM matriculas 
            WHERE id_estudiante = ? 
            ORDER BY anio_escolar DESC 
            LIMIT 1";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_estudiante);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $id_grado = $row['id_grado'];

        // Insertar calificación con el grado correcto
        $sql = "INSERT INTO calificaciones 
                   (id_estudiante, id_asignatura, id_grado, lapso, nota, observacion, fecha_registro)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iiissss", $id_estudiante, $id_asignatura, $id_grado, $lapso, $nota, $observacion, $fecha_registro);

        if ($stmt->execute()) {
            header("Location: ../index.php?mensaje=calificacion_agregada#calificacion");
            exit;
        } else {
            header("Location: ../index.php?mensaje=error_agregar_calificacion#calificacion");
            exit;
        }
    } else {
        // Aviso: el estudiante no tiene matrícula
        header("Location: ../index.php?mensaje=no_matricula#calificacion");
        exit;
    }
}
?>
