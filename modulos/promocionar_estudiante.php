<?php
include 'conexion.php';

// Ejecutar promoción automática
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Obtener estudiantes con promedio >= 10
    $sql = "SELECT c.id_estudiante, AVG(c.nota) AS promedio, MAX(m.id_grado) AS grado_actual, m.anio_escolar, m.seccion
            FROM calificaciones c
            JOIN matriculas m ON c.id_estudiante = m.id_estudiante AND c.id_grado = m.id_grado
            GROUP BY c.id_estudiante, m.anio_escolar, m.seccion
            HAVING promedio >= 10";

    $resultado = $conexion->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $promocionados = 0;

        while ($fila = $resultado->fetch_assoc()) {
            $id_estudiante = $fila['id_estudiante'];
            $grado_actual = $fila['grado_actual'];
            $anio_escolar = $fila['anio_escolar'];
            $seccion = $fila['seccion'];

            // 2. Buscar el siguiente grado
            $sqlSiguiente = "SELECT id_grado, nombre_grado, turno 
                             FROM grados_niveles 
                             WHERE id_grado > ? 
                             ORDER BY id_grado ASC LIMIT 1";
            $stmtSiguiente = $conexion->prepare($sqlSiguiente);
            $stmtSiguiente->bind_param("i", $grado_actual);
            $stmtSiguiente->execute();
            $resSiguiente = $stmtSiguiente->get_result();

            if ($resSiguiente->num_rows > 0) {
                $siguiente = $resSiguiente->fetch_assoc();
                $nuevo_grado = $siguiente['id_grado'];

                // 3. Crear nueva matrícula para el siguiente año escolar
                // Ejemplo: si el año escolar es "2025-2026", se convierte en "2026-2027"
                $partes = explode("-", $anio_escolar);
                if (count($partes) == 2) {
                    $nuevo_anio = ($partes[0]+1) . "-" . ($partes[1]+1);
                } else {
                    $nuevo_anio = date("Y") . "-" . (date("Y")+1);
                }

                $stmtInsert = $conexion->prepare("INSERT INTO matriculas (id_estudiante, id_grado, seccion, turno, anio_escolar, estado)
                                                  VALUES (?, ?, ?, ?, ?, 'Activo')");
                $stmtInsert->bind_param("iisss", $id_estudiante, $nuevo_grado, $seccion, $siguiente['turno'], $nuevo_anio);

                if ($stmtInsert->execute()) {
                    $promocionados++;
                }
            }
        }

        echo "✅ Promoción automática ejecutada. Estudiantes promovidos: $promocionados";
    } else {
        echo "ℹ️ No se encontraron estudiantes con promedio ≥ 10 para promocionar.";
    }
}
?>
