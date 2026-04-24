<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar (opcional)
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- 1. LÓGICA: CREAR RUTA ---
if (isset($_POST['crear'])) {
    $stmt = $pdo->prepare("INSERT INTO rutas (destino, horario, precio, vehiculo, empresa, cupos) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['destino'], $_POST['horario'], $_POST['precio'], 
        $_POST['vehiculo'], $_POST['empresa'], $_POST['cupos']
    ]);
    header("Location: admin_rutas.php"); 
    exit();
}

// --- 2. LÓGICA: ACTUALIZAR RUTA ---
if (isset($_POST['actualizar'])) {
    $stmt = $pdo->prepare("UPDATE rutas SET destino=?, horario=?, precio=?, vehiculo=?, empresa=?, cupos=? WHERE id_ruta=?");
    $stmt->execute([
        $_POST['destino'], $_POST['horario'], $_POST['precio'], 
        $_POST['vehiculo'], $_POST['empresa'], $_POST['cupos'], $_POST['id_ruta']
    ]);
    header("Location: admin_rutas.php"); 
    exit();
}

// --- 3. LÓGICA: CARGAR DATOS PARA EDICIÓN ---
$ruta_editar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM rutas WHERE id_ruta = ?");
    $stmt->execute([$_GET['editar']]);
    $ruta_editar = $stmt->fetch();
}

// --- 4. CONSULTA: LISTAR TODAS LAS RUTAS ---
$rutas = $pdo->query("SELECT * FROM rutas ORDER BY id_ruta DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración Global - Terminal Puerto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=2.3">
</head>
<body>
    
<header>
    <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>PANEL ADMINISTRATIVO</h1>
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
    <div style="text-align: right; font-size: 0.85em; color: #666; margin-bottom: 15px;">
        Conectado como: <strong><?php echo htmlspecialchars($nombre_usuario); ?></strong>
    </div>

    <section class="caja-formulario">
        <h2 class="titulo-seccion"><?php echo $ruta_editar ? "📝 Editar Ruta Seleccionada" : "➕ Crear Nueva Ruta de Viaje"; ?></h2>
        <form method="POST" class="form-admin">
            <?php if ($ruta_editar): ?>
                <input type="hidden" name="id_ruta" value="<?php echo $ruta_editar['id_ruta']; ?>">
            <?php endif; ?>
            
            <div class="grupo-inputs" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                <div>
                    <label>Destino:</label>
                    <input type="text" name="destino" placeholder="Ej: Pasto" value="<?php echo $ruta_editar['destino'] ?? ''; ?>" required>
                </div>
                <div>
                    <label>Horario:</label>
                    <input type="text" name="horario" placeholder="Ej: 08:00 AM" value="<?php echo $ruta_editar['horario'] ?? ''; ?>" required>
                </div>
                <div>
                    <label>Precio:</label>
                    <input type="number" name="precio" placeholder="Sin puntos" value="<?php echo $ruta_editar['precio'] ?? ''; ?>" required>
                </div>
                <div>
                    <label>Cupos:</label>
                    <input type="number" name="cupos" value="<?php echo $ruta_editar['cupos'] ?? ''; ?>" required>
                </div>
                <div>
                    <label>Vehículo:</label>
                    <input type="text" name="vehiculo" placeholder="Tipo de bus" value="<?php echo $ruta_editar['vehiculo'] ?? ''; ?>">
                </div>
                <div>
                    <label>Empresa:</label>
                    <input type="text" name="empresa" placeholder="Nombre transportadora" value="<?php echo $ruta_editar['empresa'] ?? ''; ?>">
                </div>
            </div>

            <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                <button type="submit" name="<?php echo $ruta_editar ? 'actualizar' : 'crear'; ?>" class="btn-buscar">
                    <?php echo $ruta_editar ? "GUARDAR CAMBIOS" : "REGISTRAR RUTA"; ?>
                </button>
                <?php if ($ruta_editar): ?> 
                    <a href="admin_rutas.php" style="margin-left:15px; color: #e74c3c; text-decoration: none; font-weight: bold;">[X] CANCELAR</a> 
                <?php endif; ?>
            </div>
        </form>
    </section>

    <h2 class="titulo-seccion" style="margin-top: 40px;">Control de Rutas Activas</h2>
    <div class="scroll-tabla">
        <table class="tabla-profesional" style="width: 100%; border-radius: 8px; overflow: hidden;">
            <thead>
                <tr>
                    <th>Destino</th>
                    <th>Horario</th>
                    <th>Precio</th>
                    <th>Cupos</th>
                    <th>Empresa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rutas as $r): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($r['destino']); ?></strong></td>
                    <td><?php echo htmlspecialchars($r['horario']); ?></td>
                    <td>$<?php echo number_format($r['precio'], 0, ',', '.'); ?></td>
                    <td><span class="badge"><?php echo $r['cupos']; ?></span></td>
                    <td><?php echo htmlspecialchars($r['empresa']); ?></td>
                    <td>
                        <a href="admin_rutas.php?editar=<?php echo $r['id_ruta']; ?>" style="color: #2980b9; font-weight: bold;">Editar</a> | 
                        <a href="eliminar.php?id=<?php echo $r['id_ruta']; ?>" style="color: #c0392b;" onclick="return confirm('¿Seguro que desea eliminar esta ruta?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<footer style="margin-top: 50px;">
    <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Gestión Interna de Datos</p>
</footer>

</body>
</html>