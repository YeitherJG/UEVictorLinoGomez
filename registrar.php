<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registro de Usuario</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="css/style_sesion.css">

  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(135deg, #ff6ec4, #7873f5);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .register-container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      width: 100%;
      max-width: 400px;
      text-align: center;
      position: relative;
    }

    .register-container h2 {
      margin-bottom: 20px;
      color: #7873f5;
    }

    .input-group {
      margin-bottom: 15px;
      text-align: left;
    }

    .input-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .input-group input,
    .input-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .btn-register {
      width: 100%;
      padding: 12px;
      background: #7873f5;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 10px;
    }

    .btn-register:hover {
      background: #5a54d1;
    }

    .btn-back {
      margin-top: 10px;
      background: #C61717;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-back:hover {
      background: #7E0000;
    }

    .logo-left, .logo-right {
      position: absolute;
      top: 10px;
      width: 80px;
      height: auto;
    }

    .logo-left {
      left: 10px;
    }

    .logo-right {
      right: 10px;
    }

    .notification {
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 5px;
      font-weight: bold;
    }

    .success {
      background-color: #d4edda;
      color: #155724;
    }

    .error {
      background-color: #f8d7da;
      color: #721c24;
    }
  </style>
</head>
<body>

  <img src="img/VictorLinoGomez.png" alt="Logo Institución" class="logo-left">
  <img src="img/PNF.png" alt="Logo PNF" class="logo-right">

  <div class="register-container">
    <h2>Registro de Usuario</h2>

    <?php
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        $tipo = $_SESSION['mensaje_tipo'];
        echo "<div class='notification $tipo'>$mensaje</div>";
        unset($_SESSION['mensaje']);
        unset($_SESSION['mensaje_tipo']);
    }
    ?>

    <form action="procesar_registro1.php" method="POST">
      <div class="input-group">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>
      </div>

      <div class="input-group">
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" name="contraseña" required>
      </div>

      <div class="input-group">
        <label for="rol">Rol:</label>
        <select id="rol" name="rol" required>
          <option value="administrativo">Administrativo</option>
          <option value="docente">Docente</option>
        </select>
      </div>

      <button type="submit" class="btn-register">Registrar</button>
      <button type="button" class="btn-back" onclick="window.location.href='index.html'">Regresar ←</button>
    </form>
  </div>

</body>
</html>

