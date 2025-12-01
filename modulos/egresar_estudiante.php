<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_POST['id_estudiante'];
    $id_grado = $_POST['id_grado'];
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

    // Registrar egreso
    $stmt = $conexion->prepare("UPDATE matriculas SET estado='Egresado' WHERE id_estudiante=? AND id_grado=? AND anio_escolar=?");
    $stmt->bind_param("iis", $id_estudiante, $id_grado, $anio_escolar);

    if ($stmt->execute()) {
        echo "✅ Egreso registrado correctamente.";
    } else {
        echo "❌ Error al registrar egreso.";
    }
}
?>
