<?php
session_start();
require_once '../db/db_pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $domicilio = $_POST['domicilio'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $correo_electronico = $_POST['correo_electronico'] ?? '';
    $clave = $_POST['clave'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO usuario (nombre_usuario, Nombre, Apellidos, Domicilio, telefono, correo_electronico, clave)
                               VALUES (:nombre_usuario, :nombre, :apellidos, :domicilio, :telefono, :correo_electronico, :clave)");

        $stmt->execute([
            ':nombre_usuario' => $nombre_usuario,
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':domicilio' => $domicilio,
            ':telefono' => $telefono,
            ':correo_electronico' => $correo_electronico,
            ':clave' => $clave
        ]);

        $_SESSION['usuario'] = $nombre;
        header('Location: ../principal/index.php');
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

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
    <?php endif; ?>

    <form method="post">
        <input name="nombre_usuario" class="form-control mb-2" placeholder="Nombre de usuario" required>
        <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
        <input name="apellidos" class="form-control mb-2" placeholder="Apellidos" required>
        <input name="domicilio" class="form-control mb-2" placeholder="Domicilio" required>
        <input name="telefono" class="form-control mb-2" placeholder="Teléfono" required>
        <input name="correo electronico" class="form-control mb-2" placeholder="Correo electrónico" required>
        <input name="clave" type="password" class="form-control mb-2" placeholder="Contraseña" required>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</body>

</html>
<?php unset($_SESSION['error']); ?>
