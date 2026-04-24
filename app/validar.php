<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['usuario']);
    $pass = trim($_POST['password']);

    // --- ACCESO DE EMERGENCIA (Si la DB falla, esto te deja entrar) ---
    if ($user === 'admin' && $pass === '123') {
        $_SESSION['admin'] = 'admin';
        header("Location: admin_rutas.php");
        exit();
    }
    // ------------------------------------------------------------------

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$user]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $hash_db = trim($usuario['password']);
            if (password_verify($pass, $hash_db) || $pass === $hash_db) {
                $_SESSION['admin'] = $usuario['usuario'];
                header("Location: admin_rutas.php");
                exit();
            }
        }
        
        header("Location: login.php?error=1");
        exit();

    } catch (PDOException $e) {
        // Si hay error de base de datos, lo mostramos para saber qué pasa
        die("Error crítico de conexión: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit();
}