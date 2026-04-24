<?php
session_start();
session_unset();
session_destroy();

// Esta línea te enviará al login de pasajeros (el verde)
header("Location: login_cliente.php"); 
exit();
?>