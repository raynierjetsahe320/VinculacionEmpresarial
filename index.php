
<?php
session_start();

require_once 'config/database.php';

if(isset($_SESSION['usuario'])){
    header('Location: dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login FES Aragón</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-box">
    <h1>FES Aragón</h1>

    <form action="login.php" method="POST">
        <input type="email" name="correo" placeholder="Correo">
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit">Iniciar Sesión</button>
    </form>
</div>

</body>
</html>
