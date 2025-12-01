<?php
// Incluir archivo de conexión a la base de datos
include 'conexion.php';
session_start(); // Iniciar la sesión

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contrasena'];
    $rol = $_POST['rol']; // Obtener el rol del formulario

    // Validar los datos del formulario (opcional)
    // ... (Añade aquí validación de campos) ...

    // Codificar la contraseña para comparación segura
    $contraseña_codificada = password_hash($contraseña, PASSWORD_DEFAULT); // Usa password_hash()

    // Consulta para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (usuario, contrasena, rol) VALUES ('$usuario', '$contraseña_codificada', '$rol')";

    try {
        // Intentar ejecutar la consulta
        if (mysqli_query($conexion, $sql)) {
            // Registro exitoso
            $_SESSION['mensaje'] = "Registro exitoso!";
            $_SESSION['mensaje_tipo'] = "success"; // Tipo de mensaje
        }
    } catch (mysqli_sql_exception $e) {
        // Capturar el error y almacenar en la sesión
        $_SESSION['mensaje'] = "Error: El usuario ya existe. Por favor elige otro.";
        $_SESSION['mensaje_tipo'] = "error"; // Tipo de mensaje
    }

    // Cerrar la conexión
    mysqli_close($conexion);

    // Redirigir a registro.php
    header("Location: registrar.php");
    exit(); // Asegurarse de que no se ejecute más código después de redirigir
}
?>
