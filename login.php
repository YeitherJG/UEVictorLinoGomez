<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['usuario'];
    $password = $_POST['contrasenza'];

    // Preparar consulta con PDO
    $sql = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':usuario', $username, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($password, $row['contrasena'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['usuario'] = $row['usuario']; // opcional
            header("Location: index.php"); // ✅ Panel principal
            exit();
        } else {
            header("Location: index.html?error=contraseña");
            exit();
        }
    } else {
        header("Location: index.html?error=usuario");
        exit();
    }
}
?>

