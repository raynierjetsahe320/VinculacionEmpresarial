
<?php
session_start();

if(!isset($_SESSION['usuario'])){
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="sidebar">
    <h2>FES Aragón</h2>

    <ul>
        <li>Dashboard</li>
        <li>Incidencias</li>
        <li>Baños</li>
        <li>Salones</li>
        <li>Reportes</li>
    </ul>
</div>

<div class="content">

<h1>Dashboard General</h1>

<div class="cards">

<div class="card">
<h2>152</h2>
<p>Incidencias activas</p>
</div>

<div class="card">
<h2>28</h2>
<p>Baños fuera de servicio</p>
</div>

<div class="card">
<h2>A6</h2>
<p>Edificio con más reportes</p>
</div>

</div>

<div class="chart-container">
<canvas id="grafica"></canvas>
</div>

</div>

<script>
const ctx = document.getElementById('grafica');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['A1','A2','A3','A4','A5','A6'],
        datasets: [{
            label: 'Incidencias',
            data: [12,19,8,15,20,31]
        }]
    }
});
</script>

</body>
</html>
