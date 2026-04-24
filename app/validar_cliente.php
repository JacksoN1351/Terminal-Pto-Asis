<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    // Verificamos la contraseña encriptada
    if ($usuario && password_verify($password, $usuario['password_hash'])) {
        $_SESSION['cliente_id'] = $usuario['id_cliente'];
        header("Location: index.php"); // Asegúrate de que index.php exista
        exit();
    } else {
        // Si falla, nos manda de vuelta con el error que ves en rojo
        header("Location: login_cliente.php?error=1");
        exit();
    }
}