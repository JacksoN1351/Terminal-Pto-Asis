<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Pasajeros - Terminal Pto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=1.8">
</head>
<body class="body-login">
    <header>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>TERMINAL PUERTO ASÍS</h1>
        <p>Área de Pasajeros</p>
    </header>

    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        
        <?php if(isset($_GET['registro']) && $_GET['registro'] == 'exitoso'): ?>
            <div class="success-msg">¡Registro exitoso! Ya puedes ingresar.</div>
        <?php endif; ?>

        <?php if(isset($_GET['error'])): ?>
            <p class="error-msg">Correo o contraseña incorrectos.</p>
        <?php endif; ?>
        
        <form action="validar_cliente.php" method="POST" class="login-form">
            <input type="email" name="email" placeholder="Tu correo electrónico" required>
            <input type="password" name="password" placeholder="Tu contraseña" required>
            <button type="submit" class="btn-cliente">INGRESAR</button>
        </form>

        <div class="login-footer">
            <p>¿No tienes cuenta? <a href="registro_cliente.php">Regístrate aquí</a></p>
            <hr>
            <a href="login.php" class="link-admin-acceso">Acceso Administrativo →</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Terminal de Transporte de Puerto Asís - Putumayo.</p>
    </footer>
</body>
</html>