<?php
session_start();
date_default_timezone_set('Europe/Madrid'); // Fija la hora a Madrid

require_once '../../db_pdo.php';

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// Procesar la reserva si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero_licencia'])) {
    $nombre_usuario = $_SESSION['usuario'];
    $numero_licencia = trim($_POST['numero_licencia']);
    $fecha_recogida = trim($_POST['fecha_recogida']);
    $hora_recogida = trim($_POST['hora_recogida']);
    $direccion_recogida = trim($_POST['direccion_recogida']);

    // Validar que todos los campos estén completos
    if ($nombre_usuario && $numero_licencia && $fecha_recogida && $hora_recogida && $direccion_recogida) {
        try {
            $sql = "INSERT INTO reservas 
                    (nombre_usuario, numero_licencia, fecha_recogida, hora_recogida, direccion_recogida, estado)
                    VALUES 
                    (:nombre_usuario, :numero_licencia, :fecha_recogida, :hora_recogida, :direccion_recogida, 'pendiente')";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nombre_usuario' => $nombre_usuario,
                ':numero_licencia' => $numero_licencia,
                ':fecha_recogida' => $fecha_recogida,
                ':hora_recogida' => $hora_recogida,
                ':direccion_recogida' => $direccion_recogida
            ]);

            $_SESSION['exito'] = 'Reserva realizada exitosamente';
            header('Location: index.php');
            exit;

        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al realizar la reserva: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'Por favor, completa todos los campos de la reserva';
    }
}

// Hora actual en formato HH:MM
$hora_actual = date('H:i');

// Obtener todos los taxistas ordenados alfabéticamente por nombre y apellidos
$stmt = $pdo->query("SELECT numero_licencia, nombre, apellidos, telefono, horario FROM taxistas ORDER BY nombre ASC, apellidos ASC");
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
    <link href="../../css/paginas.css" rel="stylesheet">
</head>
<body>
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

    <?php if (!empty($_SESSION['exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['exito']; ?>
            <?php unset($_SESSION['exito']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (count($taxistas_disponibles) === 0): ?>
        <p>No hay taxistas disponibles en este momento.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($taxistas_disponibles as $taxista): ?>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong><?= htmlspecialchars($taxista['nombre'] . ' ' . $taxista['apellidos']) ?></strong><br>
                            <small>Teléfono: <?= htmlspecialchars($taxista['telefono']) ?></small><br>
                            <small>Horario: <?= htmlspecialchars($taxista['horario']) ?></small>
                        </div>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalReserva<?= htmlspecialchars($taxista['numero_licencia']) ?>">
                            Reservar
                        </button>
                    </div>
                </div>

                <!-- Modal de Reserva -->
                <div class="modal fade" id="modalReserva<?= htmlspecialchars($taxista['numero_licencia']) ?>" tabindex="-1" aria-labelledby="labelReserva" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="labelReserva">Reservar taxi con <?= htmlspecialchars($taxista['nombre'] . ' ' . $taxista['apellidos']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST">
                                <div class="modal-body">
                                    <input type="hidden" name="numero_licencia" value="<?= htmlspecialchars($taxista['numero_licencia']) ?>">

                                    <div class="mb-3">
                                        <label for="fecha_recogida<?= htmlspecialchars($taxista['numero_licencia']) ?>" class="form-label">Fecha de recogida</label>
                                        <input type="date" class="form-control" id="fecha_recogida<?= htmlspecialchars($taxista['numero_licencia']) ?>" name="fecha_recogida" required min="<?= date('Y-m-d') ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="hora_recogida<?= htmlspecialchars($taxista['numero_licencia']) ?>" class="form-label">Hora de recogida</label>
                                        <input type="time" class="form-control" id="hora_recogida<?= htmlspecialchars($taxista['numero_licencia']) ?>" name="hora_recogida" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="direccion_recogida<?= htmlspecialchars($taxista['numero_licencia']) ?>" class="form-label">Dirección de recogida</label>
                                        <input type="text" class="form-control" id="direccion_recogida<?= htmlspecialchars($taxista['numero_licencia']) ?>" name="direccion_recogida" placeholder="Ej: Calle Principal, 123" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Confirmar reserva</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
