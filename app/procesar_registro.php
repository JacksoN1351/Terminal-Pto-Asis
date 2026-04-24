<?php
// 1. Conexión a la base de datos
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 2. Recibir y limpiar datos para evitar espacios accidentales
    $nombre   = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';

    // 3. VALIDACIONES DE SEGURIDAD (No quitar esta parte)
    if (empty($nombre) || empty($email) || empty($password)) {
        header("Location: registro_cliente.php?error=campos_vacios");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: registro_cliente.php?error=email_invalido");
        exit();
    }

    // 4. ENCRIPTAR la contraseña (Vital para que password_verify funcione después)
    $pass_cifrada = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 5. Preparar la consulta SQL con los nombres exactos de tu tabla
        $sql = "INSERT INTO clientes (nombre, email, password_hash, telefono, fecha_registro) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
        $stmt = $pdo->prepare($sql);
        
        // 6. Ejecutar la inserción
        if ($stmt->execute([$nombre, $email, $pass_cifrada, $telefono])) {
            // Registro exitoso -> Redirigir al login
            header("Location: login_cliente.php?registro=exitoso");
            exit();
        } else {
            header("Location: registro_cliente.php?error=error_insercion");
            exit();
        }

    } catch (PDOException $e) {
        // Manejo de errores específicos (como correo duplicado)
        if ($e->getCode() == 23000) { 
            header("Location: registro_cliente.php?error=email_duplicado");
        } else {
            // En desarrollo es útil ver el error real, pero en producción se usa un mensaje genérico
            die("Error crítico al registrar: " . $e->getMessage());
        }
        exit();
    }
} else {
    // Si alguien intenta entrar a este archivo sin enviar el formulario
    header("Location: registro_cliente.php");
    exit();
}