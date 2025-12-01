<?php
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conexion, $_POST['usuario']);
    $password = $_POST['contrasenza'];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
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

    $stmt->close();
    $conexion->close();
}
?>
