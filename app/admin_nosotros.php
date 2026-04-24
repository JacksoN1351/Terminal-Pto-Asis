<?php
session_start();
require_once 'conexion.php';

// Nombre de usuario para mostrar
$nombre_usuario = isset($_SESSION['admin']) ? $_SESSION['admin'] : "Administrador";

// --- 1. CARGAR DATOS ACTUALES ---
$stmt = $pdo->prepare("SELECT contenido FROM contenido_paginas WHERE seccion = 'nosotros'");
$stmt->execute();
$data = $stmt->fetch();

// Si no existe, usamos los textos por defecto
$nosotros = $data ? json_decode($data['contenido'], true) : [
    'titulo' => 'Nuestra Historia y Compromiso',
    'parrafo1' => 'En la Terminal de Transportes de Puerto Asís, somos el corazón de la movilidad en el Putumayo y el principal puente de conexión entre nuestra región y el resto de Colombia.',
    'parrafo2' => 'Trabajamos de la mano con las empresas transportistas más importantes del país para garantizar que cada viajero disfrute de una experiencia cómoda, puntual y eficiente.'
];

// --- 2. LÓGICA PARA GUARDAR CAMBIOS ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['guardar'])) {
    $nuevo_contenido = json_encode([
        'titulo' => $_POST['titulo'],
        'parrafo1' => $_POST['parrafo1'],
        'parrafo2' => $_POST['parrafo2']
    ]);

    if ($data) {
        $sql = "UPDATE contenido_paginas SET contenido = ? WHERE seccion = 'nosotros'";
    } else {
        $sql = "INSERT INTO contenido_paginas (seccion, contenido) VALUES ('nosotros', ?)";
    }

    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nuevo_contenido])) {
        header("Location: admin_nosotros.php?msj=ok");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin - Editar Nosotros</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
    <style>
        .form-nosotros { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .campo { margin-bottom: 25px; display: flex; flex-direction: column; }
        .campo label { font-weight: bold; color: #003366; margin-bottom: 8px; font-size: 0.95rem; }
        .campo input, .campo textarea { padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit; font-size: 1rem; transition: border 0.3s; }
        .campo input:focus, .campo textarea:focus { border-color: #003366; outline: none; }
        .alerta { background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 25px; text-align: center; font-weight: bold; }
        .titulo-seccion-admin { color: #003366; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="btn-salir" style="background: #27ae60;">VER SITIO PÚBLICO</a>
    <img src="../img/logo.png" class="logo" alt="Logo">
    <h1>CONFIGURACIÓN DE PÁGINA</h1>
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
        <div class="alerta">✅ Sección "Nosotros" actualizada con éxito.</div>
    <?php endif; ?>

    <section class="form-nosotros">
        <h2 class="titulo-seccion-admin">📝 Editar Información Institucional</h2>
        <form method="POST">
            <div class="campo">
                <label>Título Principal:</label>
                <input type="text" name="titulo" value="<?php echo htmlspecialchars($nosotros['titulo']); ?>" placeholder="Ej: Quiénes Somos" required>
            </div>

            <div class="campo">
                <label>Primer Párrafo (Introducción):</label>
                <textarea name="parrafo1" rows="5" placeholder="Escribe aquí el texto introductorio..." required><?php echo htmlspecialchars($nosotros['parrafo1']); ?></textarea>
            </div>

            <div class="campo">
                <label>Segundo Párrafo (Misión y Visión):</label>
                <textarea name="parrafo2" rows="5" placeholder="Escribe aquí los detalles del compromiso..." required><?php echo htmlspecialchars($nosotros['parrafo2']); ?></textarea>
            </div>

            <button type="submit" name="guardar" class="btn-buscar" style="width: 100%; padding: 15px; font-size: 1.1rem;">GUARDAR CAMBIOS EN LA WEB</button>
        </form>
    </section>
</div>

<footer>
    <p>&copy; 2026 Terminal Puerto Asís - Putumayo | Editor de Contenidos</p>
</footer>

</body>
</html>