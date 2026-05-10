<?php
session_start();
require_once '../db_pdo.php';

if (isset($_SESSION['usuario'])) {
    header('Location: ../principal/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave = $_POST['clave'] ?? '';
    $usuario = [
        'nombre_usuario' => trim($_POST['nombre_usuario'] ?? ''),
        'nombre' => trim($_POST['nombre'] ?? ''),
        'apellidos' => trim($_POST['apellidos'] ?? ''),
        'domicilio' => trim($_POST['domicilio'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'correo_electronico' => trim($_POST['correo_electronico'] ?? ''),
        'clave' => password_hash($clave, PASSWORD_DEFAULT),
    ];

    try {
        if (!$usuario['nombre_usuario'] || !$usuario['nombre'] || !$usuario['correo_electronico'] || !$clave) {
            $_SESSION['error'] = 'Completa todos los campos obligatorios';
            header('Location: index.php');
            exit;
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :nombre_usuario");
        $stmt->execute([':nombre_usuario' => $usuario['nombre_usuario']]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Ese nombre de usuario ya existe';
            header('Location: index.php');
            exit;
        }

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo_electronico = :correo_electronico");
        $stmt->execute([':correo_electronico' => $usuario['correo_electronico']]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = 'Ese correo electronico ya esta registrado';
            header('Location: index.php');
            exit;
        }

        $sql = "INSERT INTO usuarios
                (nombre_usuario, nombre, apellidos, domicilio, correo_electronico, telefono, clave)
                VALUES
                (:nombre_usuario, :nombre, :apellidos, :domicilio, :correo_electronico, :telefono, :clave)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($usuario);

        $_SESSION['exito'] = 'Usuario registrado correctamente. Ya puedes iniciar sesion.';
        header('Location: ../index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error al registrar usuario: ' . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuario</title>
    <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Registro de Usuario</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post">
        <input name="nombre_usuario" class="form-control mb-2" placeholder="Nombre de usuario" required>
        <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
        <input name="apellidos" class="form-control mb-2" placeholder="Apellidos" required>
        <input name="domicilio" class="form-control mb-2" placeholder="Domicilio" required>
        <input name="telefono" class="form-control mb-2" placeholder="Telefono" required>
        <input name="correo_electronico" type="email" class="form-control mb-2" placeholder="Correo electronico" required>
        <input name="clave" type="password" class="form-control mb-2" placeholder="Contrasena" required>
        <button type="submit" class="btn btn-primary">Registrarse</button>
        <a href="../index.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>
