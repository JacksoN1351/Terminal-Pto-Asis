<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- 1. CARGAR DATOS ACTUALES ---
$stmt = $pdo->prepare("SELECT contenido FROM contenido_paginas WHERE seccion = 'contacto'");
$stmt->execute();
$data = $stmt->fetch();

// Si no existe, creamos un arreglo vacío para evitar errores
$contacto = $data ? json_decode($data['contenido'], true) : [
    'telefono' => '',
    'email' => '',
    'direccion' => '',
    'mapa' => ''
];

// --- 2. LÓGICA PARA GUARDAR CAMBIOS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar'])) {
    $nuevo_contenido = json_encode([
        'telefono' => $_POST['telefono'],
        'email' => $_POST['email'],
        'direccion' => $_POST['direccion'],
        'mapa' => $_POST['mapa']
    ]);

    if ($data) {
        $sql = "UPDATE contenido_paginas SET contenido = ? WHERE seccion = 'contacto'";
        $stmt = $pdo->prepare($sql);
    } else {
        $sql = "INSERT INTO contenido_paginas (seccion, contenido) VALUES ('contacto', ?)";
        $stmt = $pdo->prepare($sql);
    }

    if ($stmt->execute([$nuevo_contenido])) {
        header("Location: admin_contacto.php?msj=ok");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Editar Contacto</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
    <style>
        .form-contacto { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .campo { margin-bottom: 20px; display: flex; flex-direction: column; }
        .campo label { font-weight: bold; color: #003366; margin-bottom: 8px; font-size: 0.95rem; }
        .campo input, .campo textarea { padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border 0.3s; }
        .campo input:focus, .campo textarea:focus { border-color: #003366; outline: none; }
        .alerta { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 25px; text-align: center; font-weight: bold; }
        .titulo-seccion-admin { color: #003366; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .instruccion-mapa { color: #666; font-size: 0.85rem; margin-top: 5px; background: #f9f9f9; padding: 5px 10px; border-radius: 4px; }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>CONFIGURACIÓN DE CONTACTO</h1>
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
        <div class="alerta">✅ Información de contacto actualizada correctamente.</div>
    <?php endif; ?>

    <section class="form-contacto">
        <h2 class="titulo-seccion-admin">📞 Canales de Atención Directa</h2>
        <form method="POST">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="campo">
                    <label>Teléfono de Atención:</label>
                    <input type="text" name="telefono" value="<?php echo htmlspecialchars($contacto['telefono']); ?>" placeholder="Ej: +57 320 000 0000" required>
                </div>

                <div class="campo">
                    <label>Correo Electrónico:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($contacto['email']); ?>" placeholder="Ej: contacto@terminalpa.com" required>
                </div>
            </div>

            <div class="campo">
                <label>Dirección Física de la Terminal:</label>
                <input type="text" name="direccion" value="<?php echo htmlspecialchars($contacto['direccion']); ?>" placeholder="Ej: Calle 10 # 20-30, Puerto Asís" required>
            </div>

            <div class="campo">
                <label>Mapa Interactivo (Iframe de Google Maps):</label>
                <textarea name="mapa" rows="4" placeholder="Pega aquí el código <iframe> del mapa"><?php echo htmlspecialchars($contacto['mapa']); ?></textarea>
                <div class="instruccion-mapa">
                    💡 <strong>¿Cómo obtenerlo?</strong> Ve a Google Maps > Busca la Terminal > Compartir > Insertar mapa > Copiar HTML.
                </div>
            </div>

            <button type="submit" name="guardar" class="btn-buscar" style="width: 100%; padding: 15px; font-size: 1.1rem; margin-top: 10px;">GUARDAR INFORMACIÓN DE CONTACTO</button>
        </form>
    </section>
</div>

<footer>
    <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Gestión de Atención al Cliente</p>
</footer>

</body>
</html>