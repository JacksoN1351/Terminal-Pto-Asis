<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta - Terminal Puerto Asís</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { color: #333; margin-bottom: 20px; }
        input { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #003366; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        button:hover { background: #002244; }
        .links { margin-top: 15px; text-align: center; }
        a { color: #003366; text-decoration: none; font-size: 0.9em; }
        .success { color: green; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="card">
    <h2>Crear Cuenta de Pasajero</h2>

    <?php if(isset($_GET['registro']) && $_GET['registro'] == 'exitoso'): ?>
        <p class="success">Registro exitoso. <a href="login_cliente.php">Inicia sesión aquí</a></p>
    <?php endif; ?>

    <form action="procesar_registro.php" method="POST">
        <input type="text"     name="nombre"   placeholder="Nombre Completo" required>
        <input type="email"    name="email"    placeholder="Correo electrónico" required>
        <input type="password" name="password" placeholder="Crea una contraseña" required>
        <input type="text"     name="telefono" placeholder="Teléfono" required>
        
        <button type="submit">Registrarme</button>
    </form>

    <div class="links">
        <a href="login_cliente.php">Ya tengo cuenta</a>
    </div>
</div>

</body>
</html>