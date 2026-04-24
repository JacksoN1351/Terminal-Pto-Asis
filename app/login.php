<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Administrativo - Terminal Puerto Asís</title>
    <link rel="stylesheet" href="../css/estilos.css?v=1.9">
</head>
<body class="body-login">
    <header>
        <img src="../img/logo.png" class="logo" alt="Logo">
        <h1>SISTEMA ADMINISTRATIVO</h1>
    </header>

    <div class="login-container">
        <h2>Panel de Control</h2>
        
        <?php if(isset($_GET['error'])): ?>
            <p class="error-msg">Usuario o contraseña incorrectos</p>
        <?php endif; ?>
        
        <form action="validar.php" method="POST" class="login-form">
            <input type="text" name="usuario" placeholder="Usuario (admin)" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit" class="btn-admin">INGRESAR AL SISTEMA</button>
        </form>

        <div class="login-footer">
            <a href="index.php" class="link-volver">← Volver a la página principal</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Terminal de Transporte de Puerto Asís - Putumayo.</p>
    </footer>
</body>
</html>