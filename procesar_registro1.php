<?php
session_start();
include 'conexion.php';

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contraseña']; // 👈 mejor renombrar en HTML a "contrasena"
    $rol = $_POST['rol'];

    // Codificar la contraseña
    $contrasena_codificada = password_hash($contrasena, PASSWORD_DEFAULT);

    // Consulta con parámetros preparados
    $sql = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES (:usuario, :contrasena, :rol)";

    try {
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':contrasena', $contrasena_codificada, PDO::PARAM_STR);
        $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Registro exitoso!";
            $_SESSION['mensaje_tipo'] = "success";
        }
    } catch (PDOException $e) {
        $_SESSION['mensaje'] = "Error: El usuario ya existe o hubo un problema.";
        $_SESSION['mensaje_tipo'] = "error";
    }

    // Cerrar conexión en PDO
    $conexion = null;

    // Redirigir a registro.php
    header("Location: registrar.php");
    exit();
}
?>
