<?php
$host = "db.vppyzoezwhuvdwhrbrdq.supabase.co";
$port = "5432";
$dbname = "postgres";
$user = "postgres";
$password = "Esojgabriel2003*";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $conexion = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "✅ Conexión exitosa a Supabase!";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>


