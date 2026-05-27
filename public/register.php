<?php
session_start();
require_once __DIR__ . '/../src/auth.php';

$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $cuenta = trim($_POST['cuenta'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if($nombre === '' || $correo === '' || $cuenta === '' || $password === ''){
        $errors[] = 'Todos los campos son obligatorios.';
    }
    if(!str_ends_with($correo, '@aragon.unam.mx')){
        $errors[] = 'El correo debe terminar en @aragon.unam.mx';
    }
    if(!preg_match('/^\d{9}$/', $cuenta)){
        $errors[] = 'El número de cuenta debe tener exactamente 9 dígitos.';
    }

    if(empty($errors)){
        $ok = register_user($nombre, $correo, $cuenta, $password);
        if($ok){
            $_SESSION['user_id'] = mysqli_insert_id($GLOBALS['conexion']);
            header('Location: report_create.php');
            exit;
        } else {
            $errors[] = 'No se pudo registrar. El correo quizá ya existe.';
        }
    }
}
require_once __DIR__ . '/../templates/header.php';
?>
<h2>Registro</h2>
<?php if(!empty($errors)): ?><div class="alert"><?php echo implode('<br>', $errors); ?></div><?php endif; ?>
<form method="POST" action="register.php">
    <label>Nombre completo<br><input type="text" name="nombre" required></label><br>
    <label>Correo institucional<br><input type="email" name="correo" required placeholder="usuario@comunidad.fes.aragon"></label><br>
    <label>Número de cuenta<br><input type="text" name="cuenta" required placeholder="9 dígitos"></label><br>
    <label>Contraseña<br><input type="password" name="password" required></label><br>
    <button class="btn" type="submit">Registrarse</button>
</form>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
