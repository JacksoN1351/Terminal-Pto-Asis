<?php
session_start();
require_once 'conexion.php';

// Verificamos que el usuario esté logueado y que se haya enviado un ID
if (isset($_SESSION['cliente_id']) && isset($_POST['id_reserva'])) {
    $id_reserva = $_POST['id_reserva'];
    $id_cliente = $_SESSION['cliente_id'];

    try {
        // Solo eliminamos si la reserva pertenece al cliente logueado (por seguridad)
        $sql = "DELETE FROM reservas WHERE id_reserva = ? AND id_cliente = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_reserva, $id_cliente]);
    } catch (PDOException $e) {
        // Error silencioso para el usuario
    }
}

// Volvemos al index siempre
header("Location: index.php");
exit();
?>