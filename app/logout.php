<?php
session_start();
session_unset();
session_destroy();

// Esta línea es la que define a dónde vas al salir
// Al usar "login.php" te enviará a la pantalla azul de admin
header("Location: login.php"); 
exit();
?>