<?php
$dsn = getenv("DATABASE_URL"); // la cadena completa del pooler session mode
try {
    $conexion = new PDO($dsn, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "✅ Conexión exitosa usando pooler session mode!";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
