<?php
session_start();

// corregir ruta relativa al archivo de configuración
require_once __DIR__ . '/../config/database.php';

$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';
$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST' && $correo !== ''){
    require_once __DIR__ . '/../src/auth.php';
    $user = find_user_by_email($correo);
    if($user && $user['password'] === $password){
        // login success (plain password for now, migrate to hashes later)
        $_SESSION['user_id'] = $user['id'];
        header('Location: report_list.php');
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Iniciar sesión - FES Aragón</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="login-container">
    <div class="login-box elevated">
        <div class="logo-wrap">
            <div class="logo-mark">FA</div>
            <div class="logo-text">
                <h1>FES Aragón</h1>
                <small class="muted">Gestión de incidencias</small>
            </div>
        </div>

        <?php if(!empty($error)): ?>
            <div class="alert"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="login-form" autocomplete="off">
            <label class="field">
                <span class="field-icon">✉️</span>
                <input id="correo" type="email" name="correo" placeholder="Correo institucional" required>
            </label>

            <label class="field">
                <span class="field-icon">🔒</span>
                <input id="password" type="password" name="password" placeholder="Contraseña" required>
                <button type="button" class="toggle-pass" aria-label="Mostrar contraseña">👁️</button>
            </label>

            <button class="btn" type="submit">Iniciar Sesión</button>
        </form>

        <div class="login-footer">
            <a href="#" class="muted">¿Problemas para entrar? Contacta al administrador.</a>
        </div>
    </div>
    <div class="login-side">Bienvenido a la plataforma de gestión de incidencias.</div>
</div>

<script>
document.querySelectorAll('.toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
        const input = btn.parentElement.querySelector('input');
        if(input.type === 'password'){
            input.type = 'text';
            btn.textContent = '🙈';
        } else {
            input.type = 'password';
            btn.textContent = '👁️';
        }
    });
});
</script>

</body>
</html>
