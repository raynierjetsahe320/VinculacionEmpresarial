<?php
session_start();

// corregir ruta relativa al archivo de configuración
require_once __DIR__ . '/../config/database.php';
$correo = $_POST['correo'] ?? '';
$password = $_POST['password'] ?? '';
$error = '';

require_once __DIR__ . '/../src/auth.php';

// Registro rápido desde login (opcional)
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_action'])){
    $nombre = trim($_POST['nombre'] ?? '');
    $nuevo_correo = trim($_POST['new_correo'] ?? '');
    $cuenta = trim($_POST['cuenta'] ?? '');
    $pass = trim($_POST['new_password'] ?? '');
    $pass2 = trim($_POST['new_password_confirm'] ?? '');
    if($nombre === '' || $nuevo_correo === '' || $cuenta === '' || $pass === '' || $pass2 === ''){
        $error = 'Todos los campos de registro son obligatorios.';
    } elseif(!str_ends_with($nuevo_correo, '@aragon.unam.mx')){
        $error = 'El correo debe terminar en @aragon.unam.mx';
    } elseif($pass !== $pass2){
        $error = 'Las contraseñas no coinciden.';
    } elseif(!preg_match('/^\d{9}$/', $cuenta)){
        $error = 'El número de cuenta debe tener 9 dígitos.';
    } else {
        $ok = register_user($nombre, $nuevo_correo, $cuenta, $pass);
        if($ok){
            $uid = mysqli_insert_id($GLOBALS['conexion']);
            $_SESSION['user_id'] = $uid;
            header('Location: report_list.php'); exit;
        } else {
            $error = 'No se pudo registrar. ¿El correo ya existe?';
        }
    }
}

// Login normal
if($_SERVER['REQUEST_METHOD'] === 'POST' && $correo !== '' && !isset($_POST['register_action'])){
    $user = find_user_by_email($correo);
    if($user){
        $stored = $user['password'];
        // si está hasheada, usar password_verify
        if(password_verify($password, $stored)){
            $_SESSION['user_id'] = $user['id'];
            header('Location: report_list.php'); exit;
        } else {
            // legacy plain-text: si coinciden, re-hash y actualizar
            if($stored === $password){
                $newhash = password_hash($password, PASSWORD_DEFAULT);
                $esc = mysqli_real_escape_string($conexion, $newhash);
                mysqli_query($conexion, "UPDATE usuarios SET password='$esc' WHERE id=".intval($user['id']));
                $_SESSION['user_id'] = $user['id'];
                header('Location: report_list.php'); exit;
            }
        }
    }
    $error = 'Credenciales incorrectas';
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

        <hr>
        <h3>Registro rápido (personal académico)</h3>
        <form action="login.php" method="POST" class="login-form" autocomplete="off">
            <input type="hidden" name="register_action" value="1">
            <label>Nombre completo<br><input type="text" name="nombre" required></label><br>
            <label>Correo institucional<br><input type="email" name="new_correo" required placeholder="usuario@aragon.unam.mx"></label><br>
            <label>Número de cuenta<br><input type="text" name="cuenta" required placeholder="9 dígitos"></label><br>
            <label>Contraseña<br><input type="password" name="new_password" required></label><br>
            <label>Confirmar contraseña<br><input type="password" name="new_password_confirm" required></label><br>
            <button class="btn" type="submit">Crear cuenta</button>
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
