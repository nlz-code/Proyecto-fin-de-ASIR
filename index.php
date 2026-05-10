<?php
session_start();

// Si ya hay un usuario logueado, redirige directo a la página principal
if (isset($_SESSION['usuario'])) {
    header('Location: principal/index.php');
    exit;
}

// Guardamos el error en una variable temporal y lo eliminamos de sesión
$error = $_SESSION['error'] ?? '';
$exito = $_SESSION['exito'] ?? '';
unset($_SESSION['error']);
unset($_SESSION['exito']);
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Mobility Alliance</title>
    <link href="/bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form action="login/login.php" method="post">
            <img class="mb-4" src="/img/transporte.png" width="210" height="140">
            <h1 class="h3 mb-3 fw-normal">Inicio de sesión</h1>

            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error ?>
                </div>
            <?php endif; ?>

            <?php if ($exito): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $exito ?>
                </div>
            <?php endif; ?>

            <div class="form-floating mb-2">
                <input name="nombre_usuario" class="form-control" id="floatingUser" placeholder="Nombre de usuario" required>
                <label for="floatingUser">Nombre de usuario</label>
            </div>

            <div class="form-floating mb-2">
                <input name="clave" type="password" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                <label for="floatingPassword">Contraseña</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Acceder</button>

            <p class="mt-3">¿No tienes cuenta? <a href="usuarios/index.php">Regístrate</a></p>
            <p class="mt-5 mb-3 text-muted">&copy; Mobility Alliance, <?php echo date('Y') ?></p>
        </form>
    </main>
</body>
</html>
