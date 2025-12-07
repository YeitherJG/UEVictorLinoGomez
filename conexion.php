<?php
$host = getenv("DB_HOST");      // ej. aws-0-[region].pooler.supabase.com
$port = getenv("DB_PORT");      // 5432
$dbname = getenv("DB_NAME");    // postgres
$user = getenv("DB_USER");      // postgres
$password = getenv("DB_PASSWORD"); // tu contraseña

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $conexion = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "✅ Conexión exitosa a Supabase!";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
