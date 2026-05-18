<?php
session_start();
require_once '../db_pdo.php';
require_once '../includes/validaciones_usuario.php';

if (isset($_SESSION['usuario'])) {
    header('Location: ../principal/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clave = $_POST['clave'] ?? '';
    $validacion = validar_usuario_formulario($_POST, true);
    $usuario = $validacion['datos'];
    $usuario['clave'] = password_hash($clave, PASSWORD_DEFAULT);

    try {
        if (!empty($validacion['errores'])) {
            $_SESSION['error'] = implode(' ', $validacion['errores']);
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
        <input name="nombre_usuario" class="form-control mb-2" placeholder="Nombre de usuario" maxlength="50" pattern="[A-Za-z0-9_]{3,50}" required>
        <input name="nombre" class="form-control mb-2" placeholder="Nombre" maxlength="10" required>
        <input name="apellidos" class="form-control mb-2" placeholder="Apellidos" maxlength="100">
        <input name="domicilio" class="form-control mb-2" placeholder="Domicilio" maxlength="255">
        <input name="telefono" class="form-control mb-2" placeholder="Telefono" maxlength="9" pattern="[0-9]{9}">
        <input name="correo_electronico" type="email" class="form-control mb-2" placeholder="Correo electronico" maxlength="150" required>
        <input name="clave" type="password" class="form-control mb-2" placeholder="Contrasena" minlength="6" maxlength="72" required>
        <button type="submit" class="btn btn-primary">Registrarse</button>
        <a href="../index.php" class="btn btn-secondary">Volver</a>
    </form>
</body>
</html>
