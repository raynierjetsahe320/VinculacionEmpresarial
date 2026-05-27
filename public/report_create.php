<?php
session_start();
require_once __DIR__ . '/../src/auth.php';
ensure_logged_in();
$user = current_user();
require_once __DIR__ . '/../templates/header.php';
global $conexion;

$id = intval($_GET['id'] ?? 0);
$titulo = '';$descripcion='';$edificio_id='';$salon_id='';$zona_id='';$prioridad='Media';$tipo='General';$estado='Abierta';
if($id){
    $res = mysqli_query($conexion, "SELECT * FROM incidencias WHERE id=$id LIMIT 1");
    if($res && mysqli_num_rows($res)>0){
        $row=mysqli_fetch_assoc($res);
        if($user['rol']!=='admin' && $row['user_id'] != $user['id']){ header('Location: report_list.php'); exit; }
        $titulo=$row['titulo']; $descripcion=$row['descripcion']; $edificio_id=$row['edificio_id']; $salon_id=$row['salon_id']; $zona_id=$row['zona_id']; $prioridad=$row['prioridad']; $tipo=$row['tipo']; $estado=$row['estado'];
    }
}

// cargar edificios
$edificios = mysqli_query($conexion, "SELECT * FROM edificios ORDER BY id");
// cargar zonas
$zonas = mysqli_query($conexion, "SELECT * FROM zonas ORDER BY nombre");

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $titulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
    $edificio_id = intval($_POST['edificio_id']);
    $zona_id = intval($_POST['zona_id']);
    $salon_id = intval($_POST['salon_id']);
    $prioridad = mysqli_real_escape_string($conexion, $_POST['prioridad']);
    $tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);

    // preparar valores NULLables para FK
    $salon_sql = $salon_id > 0 ? $salon_id : 'NULL';
    $zona_sql = $zona_id > 0 ? $zona_id : 'NULL';

    if($id){
        mysqli_query($conexion, "UPDATE incidencias SET titulo='$titulo', descripcion='$descripcion', edificio_id=$edificio_id, salon_id=$salon_sql, zona_id=$zona_sql, prioridad='$prioridad', tipo='$tipo', updated_at=NOW() WHERE id=$id");
    } else {
        $uid = intval($user['id']);
        mysqli_query($conexion, "INSERT INTO incidencias(titulo, descripcion, tipo, prioridad, estado, edificio_id, salon_id, zona_id, user_id) VALUES('$titulo','$descripcion','$tipo','$prioridad','Abierta',$edificio_id,$salon_sql,$zona_sql,$uid)");
    }
    header('Location: report_list.php'); exit;
}

?>
<h2><?php echo $id ? 'Editar reporte' : 'Crear reporte'; ?></h2>
<form method="POST">
    <label>Título<br><input type="text" name="titulo" value="<?php echo htmlspecialchars($titulo); ?>" required></label><br>
    <label>Descripción<br><textarea name="descripcion" required><?php echo htmlspecialchars($descripcion); ?></textarea></label><br>
    <label>Edificio<br>
        <select name="edificio_id" id="edificio_id">
            <?php while($e = mysqli_fetch_assoc($edificios)): ?>
                <option value="<?php echo $e['id']; ?>" <?php if($e['id']==$edificio_id) echo 'selected'; ?>><?php echo htmlspecialchars($e['nombre']); ?></option>
            <?php endwhile; ?>
        </select>
    </label><br>
    <label>Zona<br>
        <select name="zona_id">
            <option value="">-- Selecciona una zona --</option>
            <?php while($z = mysqli_fetch_assoc($zonas)): ?>
                <option value="<?php echo $z['id']; ?>" <?php if($z['id']==$zona_id) echo 'selected'; ?>><?php echo htmlspecialchars($z['nombre']); ?></option>
            <?php endwhile; ?>
        </select>
    </label><br>
    <label>Prioridad<br>
        <select name="prioridad">
            <option <?php if($prioridad=='Baja') echo 'selected'; ?>>Baja</option>
            <option <?php if($prioridad=='Media') echo 'selected'; ?>>Media</option>
            <option <?php if($prioridad=='Alta') echo 'selected'; ?>>Alta</option>
        </select>
    </label><br>
    <label>Tipo<br><input type="text" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>"></label><br>
    <button class="btn" type="submit">Guardar</button>
</form>

<?php
// panel del mapa del campus para ayudar a seleccionar zonas/edificios
$rb = mysqli_query($conexion, "SELECT id, nombre FROM edificios ORDER BY id");
?>
<div class="panel" style="margin-top:18px">
    <h3 class="panel-title">Mapa del campus</h3>
    <div style="display:flex;gap:18px;align-items:flex-start">
        <div style="flex:1">
            <img src="assets/campus-map.png" alt="Mapa campus" style="max-width:100%;border-radius:8px;box-shadow:var(--shadow)">
        </div>
        <div style="width:320px;background:var(--card);padding:12px;border-radius:8px;box-shadow:var(--shadow)">
            <h4>Edificios</h4>
            <ol>
            <?php while($bb = mysqli_fetch_assoc($rb)): ?>
                <li><?php echo htmlspecialchars(fix_encoding($bb['nombre'])); ?></li>
            <?php endwhile; ?>
            </ol>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
