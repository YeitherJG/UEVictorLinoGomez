<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_POST['id_estudiante'];
    $id_grado = $_POST['id_grado'];
    $seccion = $_POST['seccion'];
    $anio_escolar = $_POST['anio_escolar'];

    // Validar existencia del estudiante
    $checkEst = $conexion->prepare("SELECT nombre, apellido FROM estudiantes WHERE id_estudiante=?");
    $checkEst->bind_param("i", $id_estudiante);
    $checkEst->execute();
    $resEst = $checkEst->get_result();

    if ($resEst->num_rows === 0) {
        die("❌ Error: El estudiante no existe.");
    }

    // Validar existencia del grado
    $checkGrado = $conexion->prepare("SELECT nombre_grado, turno FROM grados_niveles WHERE id_grado=?");
    $checkGrado->bind_param("i", $id_grado);
    $checkGrado->execute();
    $resGrado = $checkGrado->get_result();

    if ($resGrado->num_rows === 0) {
        die("❌ Error: El grado no existe.");
    }

    // Registrar retiro (ejemplo: marcar estado en tabla matriculas)
    $stmt = $conexion->prepare("UPDATE matriculas SET estado='Retirado' WHERE id_estudiante=? AND id_grado=? AND anio_escolar=? AND seccion=?");
    $stmt->bind_param("iiss", $id_estudiante, $id_grado, $anio_escolar, $seccion);

    if ($stmt->execute()) {
        echo "✅ Retiro registrado correctamente.";
    } else {
        echo "❌ Error al registrar retiro.";
    }
}
?>
