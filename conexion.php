<?php
// Usa la cadena completa del pooler en una variable de entorno DATABASE_URL
$dsn = getenv("DATABASE_URL"); 

try {
    $conexion = new PDO($dsn, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "✅ Conexión exitosa a Supabase!";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
