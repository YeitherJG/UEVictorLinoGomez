<?php
$host = "db.vppyzoezwhuvdwhrbrdq.supabase.co";
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "Esojgabriel2003*";

$conexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conexion) {
    die("Error de conexión: " . pg_last_error());
}
?>

