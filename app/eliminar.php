<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit; }
require_once 'conexion.php';

if (isset($_GET['id'])) {
    // Aquí el cambio clave: 'id_ruta' en lugar de 'id'
    $stmt = $pdo->prepare("DELETE FROM rutas WHERE id_ruta = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: admin_rutas.php");
exit();
?>