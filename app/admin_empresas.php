<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- 1. LÓGICA PARA ELIMINAR EMPRESA ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $stmt = $pdo->prepare("DELETE FROM empresas WHERE id_empresa = ?");
    $stmt->execute([$id]);
    header("Location: admin_empresas.php");
    exit();
}

// --- 2. LÓGICA PARA AGREGAR EMPRESA CON SUBIDA DE LOGO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar'])) {
    $nombre = strtoupper($_POST['nombre']);
    $archivo = $_FILES['logo_archivo']; 

    if ($archivo['name'] != "") {
        $nombre_logo = "logo_" . time() . "_" . $archivo['name'];
        $ruta_destino = "../img/" . $nombre_logo;

        if (move_uploaded_file($archivo['tmp_name'], $ruta_destino)) {
            $stmt = $pdo->prepare("INSERT INTO empresas (nombre, logo) VALUES (?, ?)");
            $stmt->execute([$nombre, $nombre_logo]);
            header("Location: admin_empresas.php?msj=ok");
        } else {
            echo "Error: No se pudo subir el logo. Verifica los permisos de la carpeta img.";
        }
    }
    exit();
}

$empresas = $pdo->query("SELECT * FROM empresas ORDER BY nombre ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestionar Empresas</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
    <style>
        .caja-admin { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; }
        .fila-empresa { display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid #f4f4f4; }
        .fila-empresa:last-child { border-bottom: none; }
        .fila-empresa img { width: 70px; height: 45px; object-fit: contain; border-radius: 4px; background: #f9f9f9; border: 1px solid #eee; }
        .btn-quitar { color: #d9534f; font-weight: bold; text-decoration: none; font-size: 0.85rem; }
        .alerta-exito { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>GESTIÓN DE EMPRESAS ALIADAS</h1>
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
        <div class="alerta-exito">✅ Empresa aliada registrada y logo subido correctamente.</div>
    <?php endif; ?>

    <section class="caja-admin">
        <h2 class="titulo-seccion">➕ Registrar Nueva Empresa</h2>
        <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 15px; flex-wrap: wrap; justify-content: center; align-items: center;">
            <input type="text" name="nombre" placeholder="Nombre de la Empresa" required style="padding:12px; border: 1px solid #ddd; border-radius: 5px; min-width: 250px;">
            
            <div style="background: #f9f9f9; padding: 8px 15px; border: 1px dashed #ccc; border-radius: 5px;">
                <label style="font-size: 0.8rem; display: block; color: #666; margin-bottom: 5px;">Logo Corporativo:</label>
                <input type="file" name="logo_archivo" accept="image/*" required>
            </div>

            <button type="submit" name="guardar" class="btn-buscar">REGISTRAR EMPRESA</button>
        </form>
    </section>

    <section class="caja-admin">
        <h2 class="titulo-seccion">🏢 Empresas en Pantalla</h2>
        <?php if(count($empresas) > 0): ?>
            <?php foreach ($empresas as $e): ?>
                <div class="fila-empresa">
                    <div style="display: flex; align-items: center; gap: 20px;">
                        <img src="../img/<?php echo $e['logo']; ?>" onerror="this.src='../img/logo.png'">
                        <div>
                            <strong style="color: #003366; font-size: 1.1rem;"><?php echo htmlspecialchars($e['nombre']); ?></strong>
                            <p style="margin: 0; font-size: 0.8rem; color: #999;">ID de Socio: #<?php echo $e['id_empresa']; ?></p>
                        </div>
                    </div>
                    <a href="admin_empresas.php?eliminar=<?php echo $e['id_empresa']; ?>" 
                       class="btn-quitar" 
                       onclick="return confirm('¿Eliminar esta empresa aliada?')">QUITAR ACCESO</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align:center; color:#999; padding: 20px;">No hay empresas aliadas registradas aún.</p>
        <?php endif; ?>
    </section>
</div>

<footer style="margin-top: 50px;">
    <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Gestión de Convenios</p>
</footer>

</body>
</html>