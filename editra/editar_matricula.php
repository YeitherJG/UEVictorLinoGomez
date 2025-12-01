<?php
include '../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir variables (usar nombres simples, no arrays tipo matricula[...])
    $id_matricula     = isset($_POST['id_matricula']) ? (int)$_POST['id_matricula'] : 0;
    $id_estudiante    = isset($_POST['id_estudiante']) ? (int)$_POST['id_estudiante'] : 0;
    $id_grado         = isset($_POST['id_grado']) ? (int)$_POST['id_grado'] : 0;
    $id_representante = isset($_POST['id_representante']) ? (int)$_POST['id_representante'] : 0;
    $anio_escolar     = isset($_POST['anio_escolar']) ? trim($_POST['anio_escolar']) : '';
    $fecha_matricula  = isset($_POST['fecha_matricula']) ? trim($_POST['fecha_matricula']) : '';

    // Validación básica
    if ($id_matricula <= 0) { echo "Falta ID de matrícula."; exit; }
    if ($id_estudiante <= 0 || $id_grado <= 0 || $id_representante <= 0) { echo "IDs inválidos."; exit; }
    if ($anio_escolar === '' || $fecha_matricula === '') { echo "Año escolar y fecha son obligatorios."; exit; }

    // Preparar UPDATE
    $sql = "UPDATE matriculas 
            SET id_estudiante = ?, 
                id_grado = ?, 
                id_representante = ?, 
                anio_escolar = ?, 
                fecha_matricula = ?
            WHERE id_matricula = ?";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo "Error al preparar: " . $conexion->error;
        exit;
    }

    $stmt->bind_param("iiissi", $id_estudiante, $id_grado, $id_representante, $anio_escolar, $fecha_matricula, $id_matricula);

    // Ejecutar una sola vez
    if (!$stmt->execute()) {
        echo "Error al ejecutar: " . $stmt->error;
        $stmt->close();
        exit;
    }

    // Verificar filas afectadas
    if ($stmt->affected_rows > 0) {
        // Redirige a la sección correcta (con hash)
        header("Location: ../index.php?mensaje=matricula_editada_correctamente#matriculas");
        $stmt->close();
        exit;
    } else {
        // Puede ser que los datos sean iguales; confirmemos si el ID existe
        $stmt->close();
        // Opcional: comprobar existencia
        $check = $conexion->prepare("SELECT COUNT(*) FROM matriculas WHERE id_matricula = ?");
        $check->bind_param("i", $id_matricula);
        $check->execute();
        $check->bind_result($existe);
        $check->fetch();
        $check->close();

        if ($existe) {
            echo "La matrícula no se actualizó porque los datos son iguales.";
        } else {
            echo "ID de matrícula inexistente.";
        }
    }
}
