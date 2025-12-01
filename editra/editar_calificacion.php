<?php
// editar/editar_calificacion.php
include '../conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_calificacion = $_POST['id_calificacion'];
    $id_estudiante   = $_POST['id_estudiante'];
    $id_asignatura   = $_POST['id_asignatura'];
    $lapso           = $_POST['lapso'];
    $nota            = $_POST['nota'];
    $observacion     = $_POST['observacion'];
    $fecha_registro  = $_POST['fecha_registro'];

    // Buscar grado desde matrícula
    $sql = "SELECT id_grado FROM matriculas 
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

        $sql = "UPDATE calificaciones 
                SET id_estudiante=?, id_asignatura=?, id_grado=?, lapso=?, nota=?, observacion=?, fecha_registro=? 
                WHERE id_calificacion=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iiissssi", $id_estudiante, $id_asignatura, $id_grado, $lapso, $nota, $observacion, $fecha_registro, $id_calificacion);
        $stmt->execute();
    }
}
if ($stmt->execute()) {
    // Redirige a index.php y refresca la sección de calificaciones
    header("Location: ../index.php#calificacion");
    exit;
} else {
    header("Location: ../index.php?mensaje=error#calificacion");
    exit;
}

?>
