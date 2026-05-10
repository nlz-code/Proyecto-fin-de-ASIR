<?php
session_start();

// Verificar que sea admin
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../db_pdo.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
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
                <li class="nav-item"><a class="nav-link" href="mensajes.php">Mensajes</a></li>
                <li class="nav-item"><a class="nav-link" href="exportar_pdf.php">Exportar PDF</a></li>
                <li class="nav-item"><a class="nav-link" href="../login/logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Panel de Administración</h1>
    <p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></p>
    <a href="exportar_pdf.php" class="btn btn-success">Exportar todos los datos en PDF</a>

    <div class="row mt-5">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Usuarios</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="card-text"><strong>' . $result['total'] . '</strong></p>';
                    ?>
                    <a href="usuarios.php" class="btn btn-primary">Gestionar</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Taxistas</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM taxistas");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="card-text"><strong>' . $result['total'] . '</strong></p>';
                    ?>
                    <a href="taxistas.php" class="btn btn-primary">Gestionar</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Reservas</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM reservas");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="card-text"><strong>' . $result['total'] . '</strong></p>';
                    ?>
                    <a href="reservas.php" class="btn btn-primary">Gestionar</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Mensajes</h5>
                    <?php
                    $stmt = $pdo->query("SELECT COUNT(*) as total FROM mensajes_contacto");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo '<p class="card-text"><strong>' . $result['total'] . '</strong></p>';
                    ?>
                    <a href="mensajes.php" class="btn btn-primary">Ver mensajes</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
