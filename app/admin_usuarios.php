<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- 1. LÓGICA PARA ELIMINAR USUARIO ---
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    // Evitar que el admin actual se borre a sí mismo
    if ($id == $_SESSION['user_id']) {
        header("Location: admin_usuarios.php?error=self_delete");
    } else {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: admin_usuarios.php?msj=eliminado");
    }
    exit();
}

// --- 2. LÓGICA PARA AGREGAR USUARIO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar'])) {
    $user = $_POST['usuario'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $rol = $_POST['rol'];

    $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, password, rol) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$user, $pass, $rol]);
        header("Location: admin_usuarios.php?msj=creado");
    } catch (PDOException $e) {
        header("Location: admin_usuarios.php?error=duplicate");
    }
    exit();
}

// --- 3. CONSULTAR USUARIOS ---
$usuarios = $pdo->query("SELECT id, usuario, rol FROM usuarios")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios - Admin</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
    <style>
        .tabla-users { width: 100%; border-collapse: collapse; margin-top: 20px; background: white; }
        .tabla-users th, .tabla-users td { border: 1px solid #ddd; padding: 12px; text-align: center; }
        .tabla-users th { background: #003366; color: white; }
        .badge-admin { background: #003366; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; }
        .badge-empleado { background: #6c757d; color: white; padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; }
        .btn-borrar { color: #d9534f; font-weight: bold; text-decoration: none; }
        
        /* Estilos para el formulario de registro */
        .registro-caja {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            text-align: center;
        }
        .form-registro {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 15px;
        }
        .form-registro input, .form-registro select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-width: 200px;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>CONFIGURACIÓN DE USUARIOS</h1>
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

    <div style="margin-bottom: 20px;">
        <?php if(isset($_GET['msj'])): ?>
            <span style="color: green; font-weight: bold;">✔️ Operación exitosa</span>
        <?php endif; ?>
    </div>

    <section class="registro-caja">
        <h3>Crear Nuevo Usuario Administrativo</h3>
        <form method="POST" class="form-registro">
            <input type="text" name="usuario" placeholder="Nombre de Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <select name="rol">
                <option value="admin">Administrador</option>
                <option value="empleado">Empleado</option>
            </select>
            <button type="submit" name="agregar" class="btn-buscar">REGISTRAR</button>
        </form>
        
        <?php if(isset($_GET['error'])): ?>
            <p style="color:red; margin-top:10px;">
                <?php 
                    if($_GET['error'] == 'duplicate') echo "⚠️ El nombre de usuario ya existe.";
                    if($_GET['error'] == 'self_delete') echo "⚠️ No puedes eliminar tu propia cuenta.";
                ?>
            </p>
        <?php endif; ?>
    </section>

    <table class="tabla-users">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td>#<?php echo $u['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($u['usuario']); ?></strong></td>
                <td>
                    <span class="<?php echo ($u['rol'] == 'admin') ? 'badge-admin' : 'badge-empleado'; ?>">
                        <?php echo strtoupper($u['rol']); ?>
                    </span>
                </td>
                <td>
                    <a href="admin_usuarios.php?eliminar=<?php echo $u['id']; ?>" 
                       class="btn-borrar" 
                       onclick="return confirm('¿Seguro que quieres quitar el acceso a este usuario?')">Quitar Acceso</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<footer>
    <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Gestión de Seguridad</p>
</footer>

</body>
</html>