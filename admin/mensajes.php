<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../db_pdo.php';

if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    try {
        $stmt = $pdo->prepare("DELETE FROM mensajes_contacto WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $_SESSION['exito'] = 'Mensaje eliminado correctamente';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al eliminar mensaje: ' . $e->getMessage();
    }
    header('Location: mensajes.php');
    exit();
}

$stmt = $pdo->query("SELECT m.*, u.nombre, u.apellidos, u.correo_electronico
                    FROM mensajes_contacto m
                    LEFT JOIN usuarios u ON m.nombre_usuario = u.nombre_usuario
                    ORDER BY m.fecha_creacion DESC");
$mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mensajes de Contacto</title>
    <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
</head>
<body>
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
                <li class="nav-item"><a class="nav-link active" href="mensajes.php">Mensajes</a></li>
                <li class="nav-item"><a class="nav-link" href="exportar_pdf.php">Exportar PDF</a></li>
                <li class="nav-item"><a class="nav-link" href="../login/logout.php">Cerrar sesion</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Mensajes de contacto</h1>

    <?php if (!empty($_SESSION['exito'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['exito']); ?></div>
        <?php unset($_SESSION['exito']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h5>Opiniones recibidas</h5>
        </div>
        <div class="card-body">
            <?php if (empty($mensajes)): ?>
                <p class="text-muted mb-0">Todavia no hay mensajes.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Usuario</th>
                                <th>Opinion</th>
                                <th>Mensaje</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mensajes as $mensaje): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($mensaje['fecha_creacion']); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($mensaje['nombre_usuario']); ?></strong><br>
                                        <small><?php echo htmlspecialchars(trim(($mensaje['nombre'] ?? '') . ' ' . ($mensaje['apellidos'] ?? ''))); ?></small><br>
                                        <small><?php echo htmlspecialchars($mensaje['correo_electronico'] ?? ''); ?></small>
                                    </td>
                                    <td>
                                        <?php if ($mensaje['opinion'] === 'me_gusta'): ?>
                                            <span class="badge bg-success">Le gusta</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Mejorable</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="mensaje-texto"><?php echo htmlspecialchars($mensaje['mensaje']); ?></td>
                                    <td>
                                        <a href="mensajes.php?eliminar=<?php echo $mensaje['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este mensaje?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
