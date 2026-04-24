<?php
$host = "localhost";
$user = "root";
$pass = "12345678"; // Por defecto en AppServ suele ser root o vacío
$db   = "terminal_puerto_asis";

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
mysqli_set_charset($conexion, "utf8");
?>