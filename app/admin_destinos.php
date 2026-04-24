<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- LÓGICA PARA AGREGAR CON SUBIDA DE IMAGEN ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $destino = strtoupper($_POST['destino']);
    $archivo = $_FILES['foto_archivo']; 

    if ($archivo['name'] != "") {
        $nombre_foto = $archivo['name'];
        $ruta_destino = "../img/" . $nombre_foto;
        
        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            $stmt = $pdo->prepare("INSERT INTO rutas (destino, imagen, horario, precio, cupos) VALUES (?, ?, '00:00', 0, 0)");
            $stmt->execute([$destino, $nombre_foto]);
            header("Location: admin_destinos.php?msj=subido");
        } else {
            echo "Error al guardar la imagen en la carpeta img. Revisa permisos.";
        }
    }
    exit();
}

// --- ELIMINAR ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM rutas WHERE id_ruta = ?");
    $stmt->execute([$id]);
    header("Location: admin_destinos.php");
    exit();
}

$destinos = $pdo->query("SELECT * FROM rutas ORDER BY destino ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestionar Destinos</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>

<header>
    <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>GESTIÓN DE DESTINOS</h1>
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

    <?php if(isset($_GET['msj']) && $_GET['msj'] == 'subido'): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold;">
            ✅ Destino agregado y foto subida correctamente.
        </div>
    <?php endif; ?>

    <section class="busqueda-caja">
        <h2 class="titulo-seccion">➕ Agregar Nuevo Destino</h2>
        <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; align-items: center;">
            <input type="text" name="destino" placeholder="Nombre de la Ciudad" required style="padding: 10px; border-radius: 5px; border: 1px solid #ddd;">
            <div style="background: #f9f9f9; padding: 5px 10px; border: 1px dashed #ccc; border-radius: 5px;">
                <label style="font-size: 0.8rem; display: block; color: #666;">Seleccionar Imagen:</label>
                <input type="file" name="foto_archivo" accept="image/*" required>
            </div>
            <button type="submit" name="agregar" class="btn-buscar">SUBIR Y PUBLICAR</button>
        </form>
    </section>

    <h2 class="titulo-seccion" style="margin-top: 40px;">📍 Destinos Actuales en Galería</h2>
    
    <div class="grid-admin" style="margin-top:20px; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
        <?php foreach ($destinos as $d): ?>
            <div style="border:1px solid #eee; padding:15px; display:flex; justify-content:space-between; align-items:center; background:#fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <img src="../img/<?php echo !empty($d['imagen']) ? $d['imagen'] : 'default.jpg'; ?>" 
                         width="60" height="50" style="object-fit:cover; border-radius: 6px; border: 1px solid #eee;">
                    <div>
                        <strong style="color: #003366; display: block;"><?php echo htmlspecialchars($d['destino']); ?></strong>
                        <small style="color: #999;">ID: #<?php echo $d['id_ruta']; ?></small>
                    </div>
                </div>
                <a href="admin_destinos.php?eliminar=<?php echo $d['id_ruta']; ?>" 
                   style="color:#e74c3c; text-decoration: none; font-weight: bold; font-size: 0.9rem;" 
                   onclick="return confirm('¿Estás seguro de eliminar este destino?')">Eliminar</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer style="margin-top: 50px;">
    <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Gestión de Contenidos Visuales</p>
</footer>

</body>
</html>