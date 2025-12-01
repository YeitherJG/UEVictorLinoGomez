<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.html"); // Pantalla de login
    exit();
}
include 'conexion.php';
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión Escolar - U.E Victor Lino Gomez</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap.css.map">
  <link rel="stylesheet" href="css/bootstrap-theme.css">
  <link rel="stylesheet" href="css/bootstrap-theme.css.map">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/light-box.css">
  <link rel="stylesheet" href="css/owl-carousel.css">
  <link rel="stylesheet" href="css/fontAwesome.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/main.js"></script>

</head>
<body>

  <!-- Navegación lateral -->
  <div class="sidebar-navigation">
    <div class="logo text-center">
  <img src="img/informatica.jpg" alt="Logo Institución" style="width:80px; margin-bottom:10px; border-radius: 50px;">
  <img src="img/Victor Lino Gomez.jpg" alt="Logo Sistema" style="width:80px; border-radius: 50px;">
  <h3>Gestión <em>Escolar</em></h3>
</div>

    <nav>
      <ul>
        <li><a href="#estudiantes"><span class="rect"></span><span class="circle"></span>Estudiantes</a></li>
        <li><a href="#maestros"><span class="rect"></span><span class="circle"></span>Maestros</a></li>
        <li><a href="#representantes"><span class="rect"></span><span class="circle"></span>Representantes</a></li>
        <li><a href="#grados"><span class="rect"></span><span class="circle"></span>Grados/Niveles</a></li>
        <li><a href="#matricula"><span class="rect"></span><span class="circle"></span>Matrícula</a></li>
        <li><a href="#asignaturas"><span class="rect"></span><span class="circle"></span>Asignaturas</a></li>
        <li><a href="#calificacion"><span class="rect"></span><span class="circle"></span>Calificaciones</a></li>
        <li><a href="#historial"><span class="rect"></span><span class="circle"></span>Historial Academico</a></li>
        <li><a href="#reportes"><span class="rect"></span><span class="circle"></span>Reportes</a></li>
        <li><a href="modulos/cerrar_sesion.php"><span class="rect"></span><span class="circle"></span>Cerrar Sesión</a></li>
      </ul>
    </nav>
  </div>

  <!-- Contenido principal -->
  <div class="page-content">

    <section id="estudiantes" class="content-section student-dashboard-section">
      <?php include 'modulos/estudiantes.php'; ?>
    </section>

    <section id="maestros" class="content-section student-dashboard-section">
      <?php include 'modulos/maestros.php'; ?>
    </section>

    <section id="representantes" class="content-section student-dashboard-section">
      <?php include 'modulos/representantes.php'; ?>
    </section>

    <section id="grados" class="content-section student-dashboard-section">
      <?php include 'modulos/grados.php'; ?>
    </section>

    <section id="matricula" class="content-section student-dashboard-section">
      <?php include 'modulos/matriculas.php'; ?>
    </section>

    <section id="asignaturas" class="content-section student-dashboard-section">
      <?php include 'modulos/asignatura.php'; ?>
    </section>
    
    <section id="calificacion" class="content-section student-dashboard-section">
      <?php include 'modulos/calificaciones.php'; ?>
    </section>

    <section id="historial" class="content-section student-dashboard-section">
      <?php include 'modulos/historial_estudiante.php'; ?>
    </section>

    <section id="reportes" class="content-section student-dashboard-section">
      <?php include 'modulos/reportes.php'; ?>
    </section>

    <section class="footer">
      <p>&copy; UNERMB-PNFI 2025. Este Sistema de Gestion escolar (SGE) fue realizado por los estudiantes Jose Ramirez, Jesus Zambrano, Alejandro Paredes y Maikel Leal, de la Universidad Nacional Experimental "Rafael Marìa Baralt" (UNERMB).</p>
    </section>

  </div>

</body>
</html>
