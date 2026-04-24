<?php
session_start();
require_once 'conexion.php';

// Verificamos que el cliente esté logueado
if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = $_SESSION['cliente_id'];
    $id_ruta = $_POST['id_ruta'];
    $fecha_cotizacion = date('Y-m-d H:i:s');

    try {
        // Ajustamos los nombres de columnas según tu base de datos
        $sql = "INSERT INTO reservas (id_cliente, id_ruta, fecha_reserva, estado) VALUES (?, ?, ?, 'Cotizado')";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$id_cliente, $id_ruta, $fecha_cotizacion])) {
            echo "<script>alert('¡Cotización realizada con éxito!'); window.location='index.php';</script>";
        }
    } catch (PDOException $e) {
        die("Error al cotizar: " . $e->getMessage());
    }
}