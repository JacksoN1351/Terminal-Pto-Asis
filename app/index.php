<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['cliente_id'])) {
    header("Location: login_cliente.php");
    exit();
}

$id_cliente = $_SESSION['cliente_id'];

// --- 1. PROCESAR REGISTRO DE COTIZACIÓN ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_ruta'])) {
    $id_ruta = $_POST['id_ruta'];
    $precio = $_POST['precio'];
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1; 
    $total = $precio * $cantidad;

    try {
        $sql = "INSERT INTO reservas (id_ruta, id_cliente, cantidad_pasajes, total_pago, estado) 
                VALUES (?, ?, ?, ?, 'pendiente')";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$id_ruta, $id_cliente, $cantidad, $total])) {
            echo "<script>alert('Cotización agregada con éxito'); window.location='index.php';</script>";
            exit();
        }
    } catch (PDOException $e) { echo "Error: " . $e->getMessage(); }
}

// --- 2. CONSULTA DE PRE-RESERVAS DEL USUARIO ---
$stmt_res = $pdo->prepare("SELECT r.*, rt.destino, rt.empresa, rt.vehiculo 
                           FROM reservas r JOIN rutas rt ON r.id_ruta = rt.id_ruta 
                           WHERE r.id_cliente = ? ORDER BY r.fecha_creacion DESC");
$stmt_res->execute([$id_cliente]);
$mis_reservas = $stmt_res->fetchAll();

// --- 3. LÓGICA DEL BUSCADOR DE RUTAS ---
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
if ($busqueda) {
    $stmt = $pdo->prepare("SELECT * FROM rutas WHERE destino LIKE ? AND cupos > 0");
    $stmt->execute(["%$busqueda%"]);
    $rutas = $stmt->fetchAll();
} else {
    $rutas = $pdo->query("SELECT * FROM rutas WHERE cupos > 0")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Terminal Puerto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
    <script>
    function cotizarConCantidad(idRuta, destino, precio) {
        let cantidad = prompt("¿Cuántos pasajes para " + destino + "?", "1");
        if (cantidad !== null && cantidad > 0) {
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php';
            let inputs = { 'id_ruta': idRuta, 'precio': precio, 'cantidad': cantidad };
            for (let key in inputs) {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = inputs[key];
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</head>
<body>

<header>
    <a href="logout_cliente.php" class="btn-salir">CERRAR SESIÓN</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>TERMINAL PUERTO ASÍS</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Buscar Tiquetes</a></li>
        <li><a href="nosotros.php">Nosotros</a></li>
        <li><a href="destinos.php">Destinos</a></li>
        <li><a href="vehiculos.php">Vehículos</a></li>
        <li><a href="empresas.php">Empresas</a></li>
        <li><a href="contacto.php">Contacto</a></li>
    </ul>
</nav>

<div class="contenedor-principal">
    <div class="busqueda-caja">
        <h2>¿A dónde quieres viajar?</h2>
        <form action="index.php" method="GET" class="busqueda-form">
            <input type="text" name="busqueda" placeholder="Ej: Cali, Pasto, Bogotá..." value="<?php echo htmlspecialchars($busqueda); ?>">
            <button type="submit" class="btn-buscar">BUSCAR RUTA</button>
        </form>
    </div>

    <?php if (!empty($mis_reservas)): ?>
    <div class="seccion-pendiente">
        <h3>Sus Cotizaciones Pendientes</h3>
        <?php foreach ($mis_reservas as $res): ?>
        <div class="card-reserva">
            <div class="reserva-detalle">
                <strong>Destino: <?php echo htmlspecialchars($res['destino']); ?></strong><br>
                <span>Total: $<?php echo number_format($res['total_pago'], 0, ',', '.'); ?> (<?php echo $res['cantidad_pasajes']; ?> pers.)</span>
                <div class="alerta-whatsapp-mini">Para finalizar su reserva, por favor comuníquese con nuestra línea oficial de WhatsApp: 320 934 8389. Allí le proporcionaremos los datos bancarios para su transferencia. Una vez enviado el soporte de pago, le haremos entrega de su tiquete oficial de forma inmediata.</div>
            </div>
            <form action="eliminar_reserva.php" method="POST">
                <input type="hidden" name="id_reserva" value="<?php echo $res['id_reserva']; ?>">
                <button type="submit" class="btn-cancelar" onclick="return confirm('¿Cancelar cotización?')">✕</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <h2 style="color:#003366; margin-bottom:20px;">Rutas Disponibles</h2>
    <div class="grid-grande">
        <?php foreach ($rutas as $ruta): ?>
        <div class="card-grande">
            <div class="cuerpo-ruta">
                <h3><?php echo htmlspecialchars($ruta['destino']); ?></h3>
                <p>🕒 <?php echo htmlspecialchars($ruta['horario']); ?></p>
                <p>💰 $<?php echo number_format($ruta['precio']); ?></p>
                <p>💺 Cupos: <?php echo $ruta['cupos']; ?></p>
                <p><small>Empresa: <?php echo htmlspecialchars($ruta['empresa']); ?></small></p>
            </div>
            <button type="button" class="btn-cotizar" onclick="cotizarConCantidad('<?php echo $ruta['id_ruta']; ?>', '<?php echo $ruta['destino']; ?>', '<?php echo $ruta['precio']; ?>')">
                COTIZAR AHORA
            </button>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    <p>&copy; 2026 Terminal Puerto Asís - Todos los derechos reservados.</p>
</footer>

</body>
</html>