<?php
// ============================================
//  Configuración y conexión a MySQL (PDO)
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = 'localhost';
$db_name = 'sitio_personal';
$db_user = 'root';   // usuario por defecto de XAMPP
$db_pass = '';       // contraseña por defecto de XAMPP (vacía)

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('No se pudo conectar a la base de datos. Revisa includes/config.php');
}

require_once __DIR__ . '/funciones.php';
