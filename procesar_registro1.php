<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';
session_start(); // Iniciar la sesión

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];
    $rol = $_POST['rol']; // Obtener el rol del formulario

    // Validar los datos del formulario (opcional)
    // ... (Añade aquí validación de campos) ...

    // Codificar la contraseña para comparación segura
    $contraseña_codificada = password_hash($contraseña, PASSWORD_DEFAULT); // Usa password_hash()

    // Consulta para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES ('$usuario', '$contraseña_codificada', '$rol')";

    try {
        // Intentar ejecutar la consulta
          if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Registro exitoso!";
        $_SESSION['mensaje_tipo'] = "success";
              
        } 
     } catch (PDOException $e) {
    // Capturar error (ej. usuario duplicado)
    $_SESSION['mensaje'] = "Error: El usuario ya existe o hubo un problema.";
    $_SESSION['mensaje_tipo'] = "error";
}

    // Cerrar conexión en PDO
    $conexion = null;

    // Redirigir a registro.php
    header("Location: registrar.php");
    exit(); // Asegurarse de que no se ejecute más código después de redirigir
}
?>


