<?php
session_start();
date_default_timezone_set('Europe/Madrid'); // Fija la hora a Madrid

require_once '../../db_pdo.php';

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// Hora actual en formato HH:MM
$hora_actual = date('H:i');

// Obtener todos los taxistas ordenados alfabéticamente por nombre y apellidos
$stmt = $pdo->query("SELECT nombre, apellidos, telefono, horario FROM taxistas ORDER BY nombre ASC, apellidos ASC");
$taxistas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filtrar solo los que están disponibles ahora
$taxistas_disponibles = array_filter($taxistas, function($taxista) use ($hora_actual) {
    list($inicio, $fin) = explode('-', $taxista['horario']);
    // Convertimos a minutos desde medianoche para comparar
    $hora_actual_min = intval(substr($hora_actual, 0, 2)) * 60 + intval(substr($hora_actual, 3, 2));
    $inicio_min = intval(substr($inicio, 0, 2)) * 60 + intval(substr($inicio, 3, 2));
    $fin_min = intval(substr($fin, 0, 2)) * 60 + intval(substr($fin, 3, 2));

    // Consideramos que si el fin es menor que el inicio, el turno cruza la medianoche
    if ($fin_min < $inicio_min) {
        return ($hora_actual_min >= $inicio_min || $hora_actual_min <= $fin_min);
    } else {
        return ($hora_actual_min >= $inicio_min && $hora_actual_min <= $fin_min);
    }
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taxistas</title>
    <link href="../../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="padding-top: 70px;">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">Mobility Alliance</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="../viajes_favoritos/index.php">Viajes favoritos</a></li>
                <li class="nav-item"><a class="nav-link" href="../contactos/index.php">Contacto</a></li>
                <li class="nav-item"><a class="nav-link" href="../perfil/index.php">Mi perfil</a></li>
                <li class="nav-item"><a class="nav-link" href="../../login/logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Taxistas disponibles ahora (Hora actual: <?= $hora_actual ?>)</h1>
    <?php if (count($taxistas_disponibles) === 0): ?>
        <p>No hay taxistas disponibles en este momento.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($taxistas_disponibles as $taxista): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($taxista['nombre'] . ' ' . $taxista['apellidos']) ?></strong><br>
                        <small>Horario: <?= htmlspecialchars($taxista['horario']) ?></small>
                    </div>
                    <button class="btn btn-outline-primary btn-sm" onclick="copiarTelefono('<?= htmlspecialchars($taxista['telefono']) ?>')">
                        Copiar número
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
<script>
function copiarTelefono(numero) {
    navigator.clipboard.writeText(numero).then(() => {
        alert('Número copiado: ' + numero);
    }).catch(err => {
        alert('Error al copiar el número');
        console.error(err);
    });
}
</script>
</body>
</html>