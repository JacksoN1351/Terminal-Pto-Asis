<?php 
session_start(); 
require_once 'conexion.php'; 

// Consultamos todas las empresas guardadas por el admin
$stmt = $pdo->query("SELECT * FROM empresas ORDER BY nombre ASC");
$aliados = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Empresas - Terminal Puerto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=<?php echo time(); ?>">
</head>
<body>
    <header>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>ALIADOS ESTRATÉGICOS</h1>
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

    <main class="contenedor-flexible">
        <section class="caja-empresas">
            <h2>Nuestras Empresas Transportadoras</h2>
            <p style="color: #666; font-size: 1.1rem; max-width: 800px; margin: 0 auto;">
                Trabajamos en conjunto con las empresas más importantes de la región para ofrecerte 
                un servicio seguro, puntual y con la mejor cobertura nacional.
            </p>

            <div class="grid-logos">
                <?php if (count($aliados) > 0): ?>
                    <?php foreach ($aliados as $emp): ?>
                        <div class="card-empresa-publica">
                            <img src="../img/<?php echo $emp['logo']; ?>" 
                                 alt="<?php echo htmlspecialchars($emp['nombre']); ?>"
                                 onerror="this.src='../img/logo.png'">
                            <div class="nombre-aliado">
                                <?php echo htmlspecialchars($emp['nombre']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="grid-column: 1 / -1; color: #999; padding: 50px;">
                        No hay empresas registradas actualmente.
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