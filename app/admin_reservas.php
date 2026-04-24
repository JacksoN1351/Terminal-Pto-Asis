<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- 1. LÓGICA PARA ELIMINAR RESERVA ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM reservas WHERE id_reserva = ?");
    $stmt->execute([$id]);
    header("Location: admin_reservas.php");
    exit();
}

// --- 2. LÓGICA PARA CAMBIAR ESTADO ---
if (isset($_GET['estado']) && isset($_GET['id'])) {
    $nuevo_estado = $_GET['estado'];
    $id = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE reservas SET estado = ? WHERE id_reserva = ?");
    $stmt->execute([$nuevo_estado, $id]);
    header("Location: admin_reservas.php");
    exit();
}

// --- 3. CONSULTA COMPLETA ---
$sql = "SELECT r.id_reserva, r.cantidad_pasajes, r.total_pago, r.estado, r.fecha_creacion, 
               c.nombre AS cliente, rt.destino, rt.horario 
        FROM reservas r
        JOIN clientes c ON r.id_cliente = c.id_cliente
        JOIN rutas rt ON r.id_ruta = rt.id_ruta
        ORDER BY r.fecha_creacion DESC";

$reservas = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestión de Reservas</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
    <style>
        .tabla-reservas { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; font-size: 0.9rem; }
        .tabla-reservas th, .tabla-reservas td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        .tabla-reservas th { background-color: #003366; color: white; }
        
        .estado { padding: 5px 10px; border-radius: 4px; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; }
        .pendiente { background: #ffeeba; color: #856404; }
        .confirmado { background: #d4edda; color: #155724; }
        .cancelado { background: #f8d7da; color: #721c24; }

        .acciones a { text-decoration: none; margin: 0 5px; font-size: 0.8rem; font-weight: bold; }
        .btn-cambio { color: #003366; border: 1px solid #003366; padding: 2px 5px; border-radius: 3px; }
        .btn-borrar { color: #cc0000; }
    </style>
</head>
<body>

    <header>
        <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>GESTIÓN DE RESERVAS / COTIZACIONES</h1>
    </header>

    <nav>
        <ul>
            <li><a href="admin_rutas.php">Rutas</a></li>
            <li><a href="admin_reservas.php">Ver Reservas</a></li>
            <li><a href="admin_usuarios.php">Usuarios</a></li>
            <li><a href="admin_destinos.php">Destinos</a></li>
            <li><a href="admin_vehiculos.php">Vehículos</a></li>
            <li><a href="admin_empresas.php">Empresas</a></li>
            <li><a href="admin_nosotros.php">Nosotros</a></li>
            <li><a href="admin_contacto.php">Contacto</a></li>
        </ul>
    </nav>

    <div class="contenedor-principal">
        <div style="text-align: right; font-size: 0.85em; color: #666; margin-bottom: 20px;">
            Conectado como: <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong>
        </div>

        <table class="tabla-reservas">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Destino</th>
                    <th>Pasajes</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($reservas) > 0): ?>
                    <?php foreach ($reservas as $res): ?>
                    <tr>
                        <td>#<?php echo $res['id_reserva']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($res['fecha_creacion'])); ?></td>
                        <td><?php echo htmlspecialchars($res['cliente']); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($res['destino']); ?></strong><br>
                            <small><?php echo $res['horario']; ?></small>
                        </td>
                        <td><?php echo $res['cantidad_pasajes']; ?></td>
                        <td>$<?php echo number_format($res['total_pago'], 0, ',', '.'); ?></td>
                        <td>
                            <span class="estado <?php echo $res['estado']; ?>">
                                <?php echo $res['estado']; ?>
                            </span>
                        </td>
                        <td class="acciones">
                            <a href="admin_reservas.php?id=<?php echo $res['id_reserva']; ?>&estado=confirmado" class="btn-cambio">Confirmar</a>
                            <a href="admin_reservas.php?id=<?php echo $res['id_reserva']; ?>&estado=cancelado" class="btn-cambio">Cancelar</a>
                            <hr style="margin: 8px 0; border: 0; border-top: 1px solid #eee;">
                            <a href="admin_reservas.php?eliminar=<?php echo $res['id_reserva']; ?>" 
                               class="btn-borrar" 
                               onclick="return confirm('¿Eliminar esta reserva definitivamente?')">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No hay reservas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <footer>
        <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Gestión Interna</p>
    </footer>

</body>
</html>