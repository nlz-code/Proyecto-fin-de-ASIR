<?php
session_start();
require_once '../../db_pdo.php';

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

$usuario_actual = $_SESSION['usuario'];
$mensaje = '';

// --- Actualizar datos ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    $accion = $_POST['accion'];

    if ($accion === 'editar') {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $domicilio = trim($_POST['domicilio'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $clave = $_POST['clave'] ?? '';

        try {
            if (!empty($clave)) {
                $clave_hashed = password_hash($clave, PASSWORD_DEFAULT);
                $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, domicilio = :domicilio, correo_electronico = :correo, telefono = :telefono, clave = :clave WHERE nombre_usuario = :usuario";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellidos' => $apellidos,
                    ':domicilio' => $domicilio,
                    ':correo' => $correo,
                    ':telefono' => $telefono,
                    ':clave' => $clave_hashed,
                    ':usuario' => $usuario_actual
                ]);
            } else {
                $sql = "UPDATE usuarios SET nombre = :nombre, apellidos = :apellidos, domicilio = :domicilio, correo_electronico = :correo, telefono = :telefono WHERE nombre_usuario = :usuario";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nombre' => $nombre,
                    ':apellidos' => $apellidos,
                    ':domicilio' => $domicilio,
                    ':correo' => $correo,
                    ':telefono' => $telefono,
                    ':usuario' => $usuario_actual
                ]);
            }
            $mensaje = 'Datos actualizados correctamente.';
        } catch (PDOException $e) {
            $mensaje = 'Error al actualizar: ' . $e->getMessage();
        }
    }

    if ($accion === 'eliminar') {
        try {
            $sql = "DELETE FROM usuarios WHERE nombre_usuario = :usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':usuario' => $usuario_actual]);
            session_destroy();
            header("Location: ../../index.php");
            exit();
        } catch (PDOException $e) {
            $mensaje = 'Error al eliminar la cuenta: ' . $e->getMessage();
        }
    }
}

// Obtener datos del usuario
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :usuario");
$stmt->execute([':usuario' => $usuario_actual]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi perfil</title>
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
                <li class="nav-item"><a class="nav-link active" href="./index.php">Mi perfil</a></li>
                <li class="nav-item"><a class="nav-link" href="../../login/logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1>Datos personales</h1>
    <p>Aquí puedes ver y editar tus datos, o eliminar tu cuenta si lo deseas.</p>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="accion" value="editar">
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Apellidos</label>
            <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Domicilio</label>
            <input type="text" name="domicilio" class="form-control" value="<?= htmlspecialchars($usuario['domicilio']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Correo electrónico</label>
            <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo_electronico']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Contraseña nueva (opcional)</label>
            <input type="password" name="clave" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Actualizar mis datos</button>
    </form>

    <hr>
    <h3>Eliminar cuenta</h3>
    <form method="POST" onsubmit="return confirm('¿Seguro que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');">
        <input type="hidden" name="accion" value="eliminar">
        <button type="submit" class="btn btn-danger">Eliminar mi cuenta</button>
    </form>
</div>

<script src="../../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
