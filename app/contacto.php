<?php 
session_start(); 
require_once 'conexion.php'; 

// 1. Buscamos los datos de contacto en la base de datos
$stmt = $pdo->prepare("SELECT contenido FROM contenido_paginas WHERE seccion = 'contacto'");
$stmt->execute();
$data = $stmt->fetch();

// 2. Si existen datos, los decodificamos. Si no, usamos los que tenías por defecto.
if ($data) {
    $contacto = json_decode($data['contenido'], true);
} else {
    $contacto = [
        'direccion' => 'Cra. 19 #11-33 Puerto Asís, Putumayo',
        'email' => 'terminalpuertoasis@gmail.com',
        'telefono' => '320 934 8389',
        'mapa' => '' // Aquí iría el iframe de Google Maps si el admin lo pega
    ];
}

// Limpiamos el número de teléfono para el enlace de WhatsApp (quitando espacios)
$whatsapp_clean = str_replace(' ', '', $contacto['telefono']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contacto - Terminal Puerto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>CENTRO DE ATENCIÓN</h1>
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
        <div class="caja-contacto">
            <h2>Información de Contacto</h2>
            
            <div class="info-p">
                <strong>📍 Dirección:</strong> 
                <?php echo htmlspecialchars($contacto['direccion']); ?>
            </div>
            
            <div class="info-p">
                <strong>📧 Email:</strong> 
                <?php echo htmlspecialchars($contacto['email']); ?>
            </div>
            
            <div class="info-p"><strong>✅ Atención Inmediata:</strong></div>
            
            <a href="https://wa.me/57<?php echo $whatsapp_clean; ?>" class="whatsapp-link">
                Escribir al WhatsApp: <?php echo htmlspecialchars($contacto['telefono']); ?>
            </a>

            <?php if (!empty($contacto['mapa'])): ?>
                <div class="mapa-contenedor" style="margin-top: 20px;">
                    <?php echo $contacto['mapa']; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Terminal de Transporte de Puerto Asís - Putumayo.</p>
    </footer>
</body>
</html>