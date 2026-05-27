<?php
session_start();
require_once __DIR__ . '/../src/auth.php';
ensure_logged_in();
$user = current_user();
require_once __DIR__ . '/../templates/header.php';

global $conexion;
if($user['rol'] === 'admin'){
  $q = mysqli_query($conexion, "SELECT i.*, u.nombre as autor, e.nombre as edificio, z.nombre as zona FROM incidencias i LEFT JOIN usuarios u ON u.id=i.user_id LEFT JOIN edificios e ON e.id=i.edificio_id LEFT JOIN zonas z ON z.id=i.zona_id ORDER BY i.created_at DESC");
} else {
  $uid = intval($user['id']);
  $q = mysqli_query($conexion, "SELECT i.*, e.nombre as edificio, z.nombre as zona FROM incidencias i LEFT JOIN edificios e ON e.id=i.edificio_id LEFT JOIN zonas z ON z.id=i.zona_id WHERE i.user_id=$uid ORDER BY i.created_at DESC");
}

?>
<h2>Reportes</h2>
<a class="btn" href="report_create.php">Nuevo reporte</a>
<table class="table">
  <thead><tr><th>ID</th><th>Título</th><th>Edificio</th><th>Zona</th><th>Prioridad</th><th>Estado</th><th>Autor</th><th>Acciones</th></tr></thead>
  <tbody>
    <?php while($row = mysqli_fetch_assoc($q)): ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
  <td><?php echo htmlspecialchars(fix_encoding($row['titulo'])); ?></td>
  <td><?php echo htmlspecialchars(fix_encoding($row['edificio'])); ?></td>
  <td><?php echo htmlspecialchars(fix_encoding($row['zona'] ?? '')); ?></td>
  <td><?php echo htmlspecialchars(fix_encoding($row['prioridad'])); ?></td>
  <td><?php echo htmlspecialchars(fix_encoding($row['estado'])); ?></td>
  <td><?php echo htmlspecialchars(fix_encoding($row['autor'] ?? $user['nombre'])); ?></td>
        <td>
          <a href="report_view.php?id=<?php echo $row['id']; ?>">Ver</a>
          <?php if($user['rol'] === 'admin' || $row['user_id'] == $user['id']): ?> |
            <a href="report_create.php?id=<?php echo $row['id']; ?>">Editar</a> |
            <a href="report_delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Eliminar?')">Borrar</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>

<?php
// panel mapa y lista de edificios
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
