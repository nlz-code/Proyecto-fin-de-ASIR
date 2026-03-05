<?php
session_start();
require_once '../db_pdo.php';

// Si ya hay un usuario logueado, lo mandamos a la principal
if (isset($_SESSION['usuario'])) {
    header('Location: ../principal/index.php');
    exit;
}

// Procesamos el formulario al enviarlo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recolectamos los datos del formulario
    $usuario = [
        'nombre_usuario'     => trim($_POST['nombre_usuario'] ?? ''),
        'nombre'             => trim($_POST['nombre'] ?? ''),
        'apellidos'          => trim($_POST['apellidos'] ?? ''),
        'domicilio'          => trim($_POST['domicilio'] ?? ''),
        'telefono'           => trim($_POST['telefono'] ?? ''),
        'correo_electronico' => trim($_POST['correo_electronico'] ?? ''),
        'clave'              => password_hash($_POST['clave'] ?? '', PASSWORD_DEFAULT),
    ];

    try {
        // Insertamos el usuario en la tabla 'usuarios'
        $sql = "INSERT INTO usuarios 
                (nombre_usuario, nombre, apellidos, domicilio, correo_electronico, telefono, clave)
                VALUES 
                (:nombre_usuario, :nombre, :apellidos, :domicilio, :correo_electronico, :telefono, :clave)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($usuario);

        // Guardamos el usuario en sesión y redirigimos a la principal
        $_SESSION['usuario'] = $usuario['nombre_usuario'];
        header('Location: ../principal/index.php');
        exit;

    } catch (PDOException $e) {
        // Guardamos el error en sesión para mostrarlo en el formulario
        $_SESSION['error'] = "Error al registrar usuario: " . $e->getMessage();
        header('Location: index.php');  // redirige al mismo formulario
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
        <div class="alert alert-danger"><?php echo $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="post">
        <input name="nombre_usuario" class="form-control mb-2" placeholder="Nombre de usuario" required>
        <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
        <input name="apellidos" class="form-control mb-2" placeholder="Apellidos" required>
        <input name="domicilio" class="form-control mb-2" placeholder="Domicilio" required>
        <input name="telefono" class="form-control mb-2" placeholder="Teléfono" required>
        <input name="correo_electronico" class="form-control mb-2" placeholder="Correo electrónico" required>
        <input name="clave" type="password" class="form-control mb-2" placeholder="Contraseña" required>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</body>
</html>
