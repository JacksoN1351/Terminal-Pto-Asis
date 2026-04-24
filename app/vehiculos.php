<?php 
session_start(); 
require_once 'conexion.php'; 

// Consultamos los vehículos
$stmt = $pdo->query("SELECT * FROM vehiculos ORDER BY id_vehiculo ASC");
$lista_vehiculos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehículos - Terminal Puerto Asís</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>

    <header>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>TERMINAL PUERTO ASÍS</h1>
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

    <main class="contenedor-vehiculos">
        <section class="caja-blanca-vehiculos">
            <h2>NUESTRO PARQUE AUTOMOTOR</h2>
            <p style="color: #666; font-size: 1.1rem; max-width: 800px; margin: 0 auto;">
                Descubre la variedad de vehículos modernos y confortables disponibles para tus traslados. 
                Garantizamos seguridad y tecnología en cada kilómetro de tu viaje.
            </p>

            <div class="grid-vehiculos">
                <?php if (count($lista_vehiculos) > 0): ?>
                    <?php foreach ($lista_vehiculos as $v): ?>
                        <div class="card-vehiculo-publico">
                            <img src="../img/<?php echo $v['imagen']; ?>" 
                                 alt="<?php echo htmlspecialchars($v['nombre']); ?>"
                                 onerror="this.src='../img/logo.png'">
                            
                            <div class="info-vehiculo">
                                <?php echo htmlspecialchars($v['nombre']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1; color: #999; padding: 50px;">
                        No hay vehículos registrados actualmente.
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