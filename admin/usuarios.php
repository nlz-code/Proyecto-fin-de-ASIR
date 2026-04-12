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
    $nombre_usuario = $_GET['eliminar'];
    try {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE nombre_usuario = :nombre_usuario AND nombre_usuario != 'admin'");
        $stmt->execute([':nombre_usuario' => $nombre_usuario]);
        $_SESSION['exito'] = 'Usuario eliminado correctamente';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al eliminar usuario: ' . $e->getMessage();
    }
    header('Location: usuarios.php');
    exit;
}

// Procesar creación/edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $correo_electronico = trim($_POST['correo_electronico'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $domicilio = trim($_POST['domicilio'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $accion = $_POST['accion'] ?? '';

    if ($nombre_usuario && $nombre && $correo_electronico) {
        try {
            if ($accion === 'crear') {
                if (!$clave) {
                    $_SESSION['error'] = 'La contraseña es requerida para crear un usuario';
                } else {
                    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO usuarios 
                        (nombre_usuario, nombre, apellidos, correo_electronico, telefono, domicilio, clave, rol)
                        VALUES 
                        (:nombre_usuario, :nombre, :apellidos, :correo_electronico, :telefono, :domicilio, :clave, 'usuario')");
                    $stmt->execute([
                        ':nombre_usuario' => $nombre_usuario,
                        ':nombre' => $nombre,
                        ':apellidos' => $apellidos,
                        ':correo_electronico' => $correo_electronico,
                        ':telefono' => $telefono,
                        ':domicilio' => $domicilio,
                        ':clave' => $clave_hash
                    ]);
                    $_SESSION['exito'] = 'Usuario creado correctamente';
                }
            } elseif ($accion === 'editar') {
                $nombre_usuario_original = $_POST['nombre_usuario_original'];
                if ($clave) {
                    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, 
                        correo_electronico = :correo_electronico, telefono = :telefono, 
                        domicilio = :domicilio, clave = :clave 
                        WHERE nombre_usuario = :nombre_usuario_original");
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':apellidos' => $apellidos,
                        ':correo_electronico' => $correo_electronico,
                        ':telefono' => $telefono,
                        ':domicilio' => $domicilio,
                        ':clave' => $clave_hash,
                        ':nombre_usuario_original' => $nombre_usuario_original
                    ]);
                } else {
                    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, 
                        correo_electronico = :correo_electronico, telefono = :telefono, 
                        domicilio = :domicilio 
                        WHERE nombre_usuario = :nombre_usuario_original");
                    $stmt->execute([
                        ':nombre' => $nombre,
                        ':apellidos' => $apellidos,
                        ':correo_electronico' => $correo_electronico,
                        ':telefono' => $telefono,
                        ':domicilio' => $domicilio,
                        ':nombre_usuario_original' => $nombre_usuario_original
                    ]);
                }
                $_SESSION['exito'] = 'Usuario actualizado correctamente';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }
        header('Location: usuarios.php');
        exit;
    }
}

// Obtener usuario a editar si existe
$usuario_editar = null;
if (isset($_GET['editar'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario");
    $stmt->execute([':nombre_usuario' => $_GET['editar']]);
    $usuario_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY nombre_usuario ASC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
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
    <h1>Gestión de Usuarios</h1>

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
            <h5><?= $usuario_editar ? 'Editar Usuario' : 'Crear Nuevo Usuario' ?></h5>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="accion" value="<?= $usuario_editar ? 'editar' : 'crear' ?>">
                <?php if ($usuario_editar): ?>
                    <input type="hidden" name="nombre_usuario_original" value="<?= htmlspecialchars($usuario_editar['nombre_usuario']) ?>">
                <?php endif; ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de usuario</label>
                        <input type="text" class="form-control" name="nombre_usuario" value="<?= $usuario_editar ? htmlspecialchars($usuario_editar['nombre_usuario']) : '' ?>" <?= $usuario_editar ? 'readonly' : 'required' ?>>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" value="<?= $usuario_editar ? htmlspecialchars($usuario_editar['nombre']) : '' ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Apellidos</label>
                        <input type="text" class="form-control" name="apellidos" value="<?= $usuario_editar ? htmlspecialchars($usuario_editar['apellidos']) : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" name="correo_electronico" value="<?= $usuario_editar ? htmlspecialchars($usuario_editar['correo_electronico']) : '' ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" name="telefono" value="<?= $usuario_editar ? htmlspecialchars($usuario_editar['telefono']) : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Domicilio</label>
                        <input type="text" class="form-control" name="domicilio" value="<?= $usuario_editar ? htmlspecialchars($usuario_editar['domicilio']) : '' ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Contraseña <?= !$usuario_editar ? '(requerida)' : '(dejar en blanco para no cambiar)' ?></label>
                    <input type="password" class="form-control" name="clave" <?= !$usuario_editar ? 'required' : '' ?>>
                </div>

                <button type="submit" class="btn btn-primary"><?= $usuario_editar ? 'Actualizar' : 'Crear' ?></button>
                <?php if ($usuario_editar): ?>
                    <a href="usuarios.php" class="btn btn-secondary">Cancelar</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="card">
        <div class="card-header">
            <h5>Listado de Usuarios</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                            <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                            <td><?= htmlspecialchars($usuario['apellidos'] ?? '') ?></td>
                            <td><?= htmlspecialchars($usuario['correo_electronico']) ?></td>
                            <td><?= htmlspecialchars($usuario['telefono'] ?? '') ?></td>
                            <td>
                                <a href="usuarios.php?editar=<?= htmlspecialchars($usuario['nombre_usuario']) ?>" class="btn btn-warning btn-sm">Editar</a>
                                <?php if ($usuario['nombre_usuario'] !== 'admin'): ?>
                                    <a href="usuarios.php?eliminar=<?= htmlspecialchars($usuario['nombre_usuario']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                                <?php endif; ?>
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