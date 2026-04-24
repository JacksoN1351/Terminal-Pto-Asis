<?php
// Configuración de la base de datos
$host = 'localhost';
$db   = 'terminal_puerto_asis';
$user = 'root'; // Usuario por defecto de XAMPP
$pass = '12345678';     // Contraseña por defecto de XAMPP (vacía)
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Creamos la conexión PDO
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // Si hay error, detenemos la ejecución y mostramos el mensaje
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
?>