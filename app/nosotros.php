<?php 
session_start(); 
require_once 'conexion.php'; 

// Buscamos los datos en la base de datos
$stmt = $pdo->prepare("SELECT contenido FROM contenido_paginas WHERE seccion = 'nosotros'");
$stmt->execute();
$data = $stmt->fetch();

if ($data) {
    $nosotros = json_decode($data['contenido'], true);
} else {
    // Valores por defecto si la base de datos está vacía
    $nosotros = [
        'titulo' => 'Nuestra Historia y Compromiso',
        'parrafo1' => 'En la Terminal de Transportes de Puerto Asís, somos el corazón de la movilidad en el Putumayo y el principal puente de conexión entre nuestra región y el resto de Colombia.',
        'parrafo2' => 'Trabajamos de la mano con las empresas transportistas más importantes del país para garantizar que cada viajero disfrute de una experiencia cómoda, puntual y eficiente.'
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nosotros - Terminal Puerto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>¿QUIÉNES SOMOS?</h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Inicio</a></li>
            <li><a href="nosotros.php">Nosotros</a></li>
            <li><a href="destinos.php">Destinos</a></li>
            <li><a href="vehiculos.php">Vehículos</a></li>
            <li><a href="empresas.php">Empresas</a></li>
            <li><a href="contacto.php">Contacto</a></li>
        </ul>
    </nav>

    <main class="contenedor-texto">
        <section class="caja-blanca">
            <h2><?php echo htmlspecialchars($nosotros['titulo']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($nosotros['parrafo1'])); ?></p>
            <p><?php echo nl2br(htmlspecialchars($nosotros['parrafo2'])); ?></p>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Terminal de Transporte de Puerto Asís - Putumayo.</p>
    </footer>
</body>
</html>