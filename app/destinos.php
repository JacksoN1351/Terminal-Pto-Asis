<?php 
session_start(); 
require_once 'conexion.php'; 

// Consultamos los destinos únicos para evitar duplicados en la galería
$stmt = $pdo->query("SELECT DISTINCT destino, imagen FROM rutas ORDER BY destino ASC");
$destinos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Destinos - Terminal Puerto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>NUESTROS DESTINOS</h1>
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

    <main class="contenedor-destinos">
        <section class="caja-blanca-destinos">
            <h2>LUGARES QUE PUEDES VISITAR</h2>
            <p style="color: #666; font-size: 1.1rem; max-width: 800px; margin: 0 auto;">
                Conectamos a Puerto Asís con los principales municipios y ciudades del país. 
                Elige tu próximo destino y viaja con la comodidad que mereces.
            </p>

            <div class="grid-destinos">
                <?php if (count($destinos) > 0): ?>
                    <?php foreach ($destinos as $d): ?>
                        <div class="card-destino-publico">
                            <?php 
                                $ruta_foto = "../img/" . $d['imagen'];
                                $foto_final = (file_exists($ruta_foto) && !empty($d['imagen'])) ? $ruta_foto : "../img/logo.png";
                            ?>
                            <img src="<?php echo $foto_final; ?>" 
                                 alt="<?php echo htmlspecialchars($d['destino']); ?>"
                                 onerror="this.src='../img/logo.png'">
                                 
                            <div class="info-destino-nombre">
                                <?php echo htmlspecialchars($d['destino']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1; color: #999; padding: 50px;">
                        Próximamente más destinos disponibles para tus viajes.
                    </p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Terminal de Transporte de Puerto Asís - Putumayo.</p>
    </footer>
</body>
</html>