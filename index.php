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
    <title>Tarifas de Transporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="signin.css" rel="stylesheet">
</head>
<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form action="login.php" method="post">
            <img class="mb-4" src="logo.png" alt="" width="72" height="57">
            <h1 class="h3 mb-3 fw-normal">Bienvenido</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['error'] ?>
                </div>
            <?php endif; ?>

            <div class="form-floating">
                <input name="gmail" class="form-control" id="floatingInput" placeholder="Gmail" required>
                <label for="floatingInput">Gmail</label>
            </div>
            <div class="form-floating">
                <input name="clave" type="password" class="form-control" id="floatingPassword" placeholder="Contraseña" required>
                <label for="floatingPassword">Contraseña</label>
            </div>

            <button class="w-100 btn btn-lg btn-primary" type="submit">Acceder</button>
            <p class="mt-5 mb-3 text-muted">&copy; <?php echo date('Y') ?></p>
        </form>
        <p>¿No tienes cuenta? <a href="usuarios/index.php">Regístrate</a></p>
    </main>
</body>
</html>
<?php unset($_SESSION['error']); ?>
