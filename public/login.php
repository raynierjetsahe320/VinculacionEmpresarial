
<?php
session_start();
require_once '../config/database.php';

$correo = $_POST['correo'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE correo='$correo'";
$resultado = mysqli_query($conexion, $sql);

if(mysqli_num_rows($resultado) > 0){
    $_SESSION['usuario'] = $correo;
    header('Location: dashboard.php');
}else{
    echo "Credenciales incorrectas";
}
?>
