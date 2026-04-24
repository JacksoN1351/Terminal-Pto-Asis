<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- 1. LÓGICA PARA ELIMINAR VEHÍCULO ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM vehiculos WHERE id_vehiculo = ?");
    $stmt->execute([$id]);
    header("Location: admin_vehiculos.php");
    exit();
}

// --- 2. LÓGICA PARA AGREGAR VEHÍCULO CON SUBIDA DE IMAGEN ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $archivo = $_FILES['foto_vehiculo']; 

    if ($archivo['name'] != "") {
        $nombre_archivo = time() . "_" . $archivo['name']; 
        $ruta_destino = "../img/" . $nombre_archivo;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            $stmt = $pdo->prepare("INSERT INTO vehiculos (nombre, imagen) VALUES (?, ?)");
            $stmt->execute([$nombre, $nombre_archivo]);
            header("Location: admin_vehiculos.php?msj=ok");
        } else {
            echo "Error: No se pudo guardar la imagen en la carpeta img. Revisa permisos.";
        }
    }
    exit();
}

$vehiculos = $pdo->query("SELECT * FROM vehiculos ORDER BY id_vehiculo DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestionar Vehículos</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
    <style>
        .caja-admin { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .fila-item { display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid #f4f4f4; }
        .fila-item:last-child { border-bottom: none; }
        .fila-item img { width: 90px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; }
        .btn-quitar { color: #d9534f; font-weight: bold; text-decoration: none; font-size: 0.9rem; }
        .alerta-exito { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>GESTIÓN DE VEHÍCULOS</h1>
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

    <?php if(isset($_GET['msj'])): ?>
        <div class="alerta-exito">✅ Vehículo agregado y foto subida con éxito.</div>
    <?php endif; ?>

    <section class="caja-admin">
        <h2 class="titulo-seccion">➕ Agregar Nuevo Tipo de Vehículo</h2>
        <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; align-items: center;">
            <input type="text" name="nombre" placeholder="Nombre (Ej: Bus Especial)" required style="padding:12px; border: 1px solid #ddd; border-radius: 5px; min-width: 250px;">
            
            <div style="background: #f9f9f9; padding: 8px 15px; border: 1px dashed #ccc; border-radius: 5px;">
                <label style="font-size: 0.8rem; display: block; color: #666; margin-bottom: 5px;">Imagen del Vehículo:</label>
                <input type="file" name="foto_vehiculo" accept="image/*" required>
            </div>

            <button type="submit" name="agregar" class="btn-buscar">SUBIR Y REGISTRAR</button>
        </form>
    </section>

    <section class="caja-admin">
        <h2 class="titulo-seccion">🚐 Vehículos en Exhibición</h2>
        <?php if(count($vehiculos) > 0): ?>
            <?php foreach ($vehiculos as $v): ?>
                <div class="fila-item">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <img src="../img/<?php echo $v['imagen']; ?>" onerror="this.src='../img/logo.png'">
                        <div>
                            <strong style="color: #003366; font-size: 1.1rem;"><?php echo htmlspecialchars($v['nombre']); ?></strong>
                            <p style="margin: 0; font-size: 0.8rem; color: #999;">Ref ID: #<?php echo $v['id_vehiculo']; ?></p>
                        </div>
                    </div>
                    <a href="admin_vehiculos.php?eliminar=<?php echo $v['id_vehiculo']; ?>" 
                       class="btn-quitar" 
                       onclick="return confirm('¿Eliminar este vehículo de la lista?')">ELIMINAR</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color: #999;">No hay vehículos registrados actualmente.</p>
        <?php endif; ?>
    </section>
</div>

<footer style="margin-top: 50px;">
    <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Gestión de Flota</p>
</footer>

</body>
</html>