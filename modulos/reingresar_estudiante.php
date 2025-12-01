<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_POST['id_estudiante'];
    $id_grado = $_POST['id_grado'];
    $seccion = $_POST['seccion'];
    $anio_escolar = $_POST['anio_escolar'];

    // Validar estudiante
    $checkEst = $conexion->prepare("SELECT id_estudiante FROM estudiantes WHERE id_estudiante=?");
    $checkEst->bind_param("i", $id_estudiante);
    $checkEst->execute();
    if ($checkEst->get_result()->num_rows === 0) {
        die("❌ Error: Estudiante no existe.");
    }

    // Validar grado
    $checkGrado = $conexion->prepare("SELECT id_grado FROM grados_niveles WHERE id_grado=?");
    $checkGrado->bind_param("i", $id_grado);
    $checkGrado->execute();
    if ($checkGrado->get_result()->num_rows === 0) {
        die("❌ Error: Grado no existe.");
    }

    // Registrar reingreso (ejemplo: actualizar estado)
    $stmt = $conexion->prepare("UPDATE matriculas SET estado='Activo' WHERE id_estudiante=? AND id_grado=? AND anio_escolar=? AND seccion=?");
    $stmt->bind_param("iiss", $id_estudiante, $id_grado, $anio_escolar, $seccion);

    if ($stmt->execute()) {
        echo "✅ Reingreso registrado correctamente.";
    } else {
        echo "❌ Error al registrar reingreso.";
    }
}
?>
