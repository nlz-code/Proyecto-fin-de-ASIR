<?php
session_start();

// Verificar que sea admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../db_pdo.php';

// Procesar eliminación
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    try {
        $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['exito'] = 'Reserva eliminada correctamente';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al eliminar reserva: ' . $e->getMessage();
    }
    header('Location: reservas.php');
    exit;
}

// Procesar actualización de estado
if (isset($_GET['cambiar_estado'])) {
    $id = $_GET['cambiar_estado'];
    $nuevo_estado = $_GET['estado'] ?? '';
    if (in_array($nuevo_estado, ['pendiente', 'confirmada', 'completada', 'cancelada'])) {
        try {
            $stmt = $pdo->prepare("UPDATE reservas SET estado = :estado WHERE id = :id");
            $stmt->execute([':estado' => $nuevo_estado, ':id' => $id]);
            $_SESSION['exito'] = 'Estado actualizado correctamente';
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
    }
    header('Location: reservas.php');
    exit;
}

// Obtener todas las reservas
$stmt = $pdo->query("SELECT r.*, u.nombre as nombre_usuario, u.apellidos, t.nombre as nombre_taxista, t.apellidos as apellidos_taxista 
                    FROM reservas r 
                    JOIN usuarios u ON r.nombre_usuario = u.nombre_usuario 
                    JOIN taxistas t ON r.numero_licencia = t.numero_licencia 
                    ORDER BY r.fecha_recogida DESC");
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reservas</title>
    <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="padding-top: 70px;">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Mobility Alliance - Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="usuarios.php">Usuarios</a></li>
                <li class="nav-item"><a class="nav-link" href="taxistas.php">Taxistas</a></li>
                <li class="nav-item"><a class="nav-link" href="reservas.php">Reservas</a></li>
                <li class="nav-item"><a class="nav-link" href="../login/logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Gestión de Reservas</h1>

    <?php if (!empty($_SESSION['exito'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['exito']; ?>
            <?php unset($_SESSION['exito']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tabla de reservas -->
    <div class="card">
        <div class="card-header">
            <h5>Listado de Reservas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Taxista</th>
                            <th>Fecha Recogida</th>
                            <th>Hora Recogida</th>
                            <th>Dirección</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['id']) ?></td>
                                <td><?= htmlspecialchars($reserva['nombre_usuario']) ?></td>
                                <td><?= htmlspecialchars($reserva['nombre_taxista'] . ' ' . $reserva['apellidos_taxista']) ?></td>
                                <td><?= htmlspecialchars($reserva['fecha_recogida']) ?></td>
                                <td><?= htmlspecialchars($reserva['hora_recogida']) ?></td>
                                <td><?= htmlspecialchars($reserva['direccion_recogida']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $reserva['estado'] === 'confirmada' ? 'success' : ($reserva['estado'] === 'completada' ? 'info' : ($reserva['estado'] === 'cancelada' ? 'danger' : 'warning')) ?>">
                                        <?= ucfirst(htmlspecialchars($reserva['estado'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="reservas.php?cambiar_estado=<?= $reserva['id'] ?>&estado=confirmada" class="btn btn-outline-success" title="Confirmar">Conf</a>
                                        <a href="reservas.php?cambiar_estado=<?= $reserva['id'] ?>&estado=completada" class="btn btn-outline-info" title="Completada">Comp</a>
                                        <a href="reservas.php?cambiar_estado=<?= $reserva['id'] ?>&estado=cancelada" class="btn btn-outline-danger" title="Cancelar">Can</a>
                                        <a href="reservas.php?eliminar=<?= $reserva['id'] ?>" class="btn btn-outline-danger" onclick="return confirm('¿Estás seguro?')">Eli</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>