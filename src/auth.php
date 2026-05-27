<?php
// Funciones simples de autenticación y utilidades
require_once __DIR__ . '/../config/database.php';

function find_user_by_email($email){
    global $conexion;
    $email_safe = mysqli_real_escape_string($conexion, $email);
    $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE correo='$email_safe' LIMIT 1");
    if($res && mysqli_num_rows($res) > 0) return mysqli_fetch_assoc($res);
    return null;
}

function register_user($nombre, $correo, $cuenta, $password){
    global $conexion;
    $nombre = mysqli_real_escape_string($conexion, $nombre);
    $correo = mysqli_real_escape_string($conexion, $correo);
    $cuenta = mysqli_real_escape_string($conexion, $cuenta);
    // almacenar password con hash
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $hash = mysqli_real_escape_string($conexion, $hash);
    $sql = "INSERT INTO usuarios(nombre, correo, cuenta, password) VALUES ('$nombre','$correo','$cuenta','$hash')";
    return mysqli_query($conexion, $sql);
}

function ensure_logged_in(){
    if(!isset($_SESSION['user_id'])){
        header('Location: login.php');
        exit;
    }
}

function current_user(){
    global $conexion;
    if(!isset($_SESSION['user_id'])) return null;
    $id = intval($_SESSION['user_id']);
    $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id=$id LIMIT 1");
    if($res && mysqli_num_rows($res) > 0) return mysqli_fetch_assoc($res);
    return null;
}

// Corrige mojibake común (ej. "BaÃ±o" -> "Baño") usando mapas simples
function fix_encoding(string $s): string {
    if($s === null) return '';
    $map = [
        'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú',
        'Ã±' => 'ñ', 'Ã‘' => 'Ñ', 'Ã‰' => 'É', 'Ã' => 'Á',
        'Ã–' => 'Ö', 'Ã–' => 'Ö', 'â' => '-', 'â' => '—',
        'â' => '"', 'â' => '"', 'â' => "'", 'Â°' => '°'
    ];
    return strtr($s, $map);
}
