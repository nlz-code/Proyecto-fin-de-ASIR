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
    $numero_licencia = $_GET['eliminar'];
    try {
        $stmt = $pdo->prepare("DELETE FROM taxistas WHERE numero_licencia = :numero_licencia");
        $stmt->execute([':numero_licencia' => $numero_licencia]);
        $_SESSION['exito'] = 'Taxista eliminado correctamente';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al eliminar taxista: ' . $e->getMessage();
    }
    header('Location: taxistas.php');
    exit;
}

// Procesar creación/edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero_licencia = trim($_POST['numero_licencia'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $horario = trim($_POST['horario'] ?? '');
    $accion = $_POST['accion'] ?? '';

    if ($numero_licencia && $nombre && $apellidos && $telefono && $horario) {
        try {
            if ($accion === 'crear') {
                $stmt = $pdo->prepare("INSERT INTO taxistas 
                    (numero_licencia, nombre, apellidos, telefono, horario)
                    VALUES 
                    (:numero_licencia, :nombre, :apellidos, :telefono, :horario)");
                $stmt->execute([
                    ':numero_licencia' => $numero_licencia,
                    ':nombre' => $nombre,
                    ':apellidos' => $apellidos,
                    ':telefono' => $telefono,
                    ':horario' => $horario
                ]);
                $_SESSION['exito'] = 'Taxista creado correctamente';
            } elseif ($accion === 'editar') {
                $numero_licencia_original = $_POST['numero_licencia_original'];
                $stmt = $pdo->prepare("UPDATE taxistas SET nombre = :nombre, apellidos = :apellidos, 
                    telefono = :telefono, horario = :horario 
                    WHERE numero_licencia = :numero_licencia_original");
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellidos' => $apellidos,
                    ':telefono' => $telefono,
                    ':horario' => $horario,
                    ':numero_licencia_original' => $numero_licencia_original
                ]);
                $_SESSION['exito'] = 'Taxista actualizado correctamente';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
        header('Location: taxistas.php');
        exit;
    }
}

// Obtener taxista a editar si existe
$taxista_editar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM taxistas WHERE numero_licencia = :numero_licencia");
    $stmt->execute([':numero_licencia' => $_GET['editar']]);
    $taxista_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todos los taxistas
$stmt = $pdo->query("SELECT * FROM taxistas ORDER BY nombre ASC");
$taxistas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Taxistas</title>
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
    <h1>Gestión de Taxistas</h1>

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

    <!-- Formulario de creación/edición -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><?= $taxista_editar ? 'Editar Taxista' : 'Crear Nuevo Taxista' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="accion" value="<?= $taxista_editar ? 'editar' : 'crear' ?>">
                <?php if ($taxista_editar): ?>
                    <input type="hidden" name="numero_licencia_original" value="<?= htmlspecialchars($taxista_editar['numero_licencia']) ?>">
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Número de Licencia</label>
                        <input type="text" class="form-control" name="numero_licencia" value="<?= $taxista_editar ? htmlspecialchars($taxista_editar['numero_licencia']) : '' ?>" <?= $taxista_editar ? 'readonly' : 'required' ?>>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?= $taxista_editar ? htmlspecialchars($taxista_editar['nombre']) : '' ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" value="<?= $taxista_editar ? htmlspecialchars($taxista_editar['apellidos']) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" name="telefono" value="<?= $taxista_editar ? htmlspecialchars($taxista_editar['telefono']) : '' ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Horario (Ej: 08:00-16:00)</label>
                    <input type="text" class="form-control" name="horario" value="<?= $taxista_editar ? htmlspecialchars($taxista_editar['horario']) : '' ?>" required>
                </div>

                <button type="submit" class="btn btn-primary"><?= $taxista_editar ? 'Actualizar' : 'Crear' ?></button>
                <?php if ($taxista_editar): ?>
                    <a href="taxistas.php" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabla de taxistas -->
    <div class="card">
        <div class="card-header">
            <h5>Listado de Taxistas</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Número Licencia</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Teléfono</th>
                        <th>Horario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($taxistas as $taxista): ?>
                        <tr>
                            <td><?= htmlspecialchars($taxista['numero_licencia']) ?></td>
                            <td><?= htmlspecialchars($taxista['nombre']) ?></td>
                            <td><?= htmlspecialchars($taxista['apellidos']) ?></td>
                            <td><?= htmlspecialchars($taxista['telefono']) ?></td>
                            <td><?= htmlspecialchars($taxista['horario']) ?></td>
                            <td>
                                <a href="taxistas.php?editar=<?= htmlspecialchars($taxista['numero_licencia']) ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="taxistas.php?eliminar=<?= htmlspecialchars($taxista['numero_licencia']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
