<?php
session_start();
require_once '../db_pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellidos = $_POST['apellidos'] ?? '';
    $domicilio = $_POST['domicilio'] ?? '';
    $telefono = $_POST['telefono'] ?? '';
    $gmail = $_POST['gmail'] ?? '';
    $clave = $_POST['clave'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO usuario (DNI, Nombre, Apellidos, Domicilio, telefono, Gmail, clave)
                               VALUES (:dni, :nombre, :apellidos, :domicilio, :telefono, :gmail, :clave)");

        $stmt->execute([
            ':dni' => $dni,
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':domicilio' => $domicilio,
            ':telefono' => $telefono,
            ':gmail' => $gmail,
            ':clave' => $clave
        ]);

        $_SESSION['usuario'] = $nombre;
        header('Location: ../principal/index.html');
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
        <input name="dni" class="form-control mb-2" placeholder="DNI" required>
        <input name="nombre" class="form-control mb-2" placeholder="Nombre" required>
        <input name="apellidos" class="form-control mb-2" placeholder="Apellidos" required>
        <input name="domicilio" class="form-control mb-2" placeholder="Domicilio" required>
        <input name="telefono" class="form-control mb-2" placeholder="Teléfono" required>
        <input name="gmail" class="form-control mb-2" placeholder="Gmail" required>
        <input name="clave" type="password" class="form-control mb-2" placeholder="Contraseña" required>
        <button type="submit" class="btn btn-primary">Registrarse</button>
    </form>
</body>

</html>
<?php unset($_SESSION['error']); ?>
