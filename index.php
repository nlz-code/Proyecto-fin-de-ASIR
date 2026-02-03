<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header('Location: principal/index.php');
    exit;
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Mobility Alliance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/signin.css" rel="stylesheet">
</head>

<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form action="login/login.php" method="post">
            <img class="mb-4" src="/img/transporte.png" width="160" height="120">
            <h1 class="h3 mb-3 fw-normal">Bienvenido a Mobility Alliance</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error'] ?>
                </div>
            <?php endif; ?>

            <div class="form-floating">
                <input name="nombre_usuario" class="form-control" id="floatingUser" placeholder="Nombre de usuario" required>
                <label for="floatingUser">Nombre de usuario</label>
            </div>

            <div class="form-floating">
                <input name="clave" type="password" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                <label for="floatingPassword">Contraseña</label>
            </div>


            <button class="w-100 btn btn-lg btn-primary" type="submit">Acceder</button>
            <p class="mt-5 mb-3 text-muted">&copy; Mobility Alliance, <?php echo date('Y') ?></p>
        </form>
        <p>¿No tienes cuenta? <a href="usuarios/index.php">Regístrate</a></p>
    </main>
</body>

</html>
<?php unset($_SESSION['error']); ?>