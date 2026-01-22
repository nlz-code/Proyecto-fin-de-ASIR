<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Tarifas de Transporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="signin.css" rel="stylesheet">
</head>
<body class="text-center">
    <main class="form-signin w-100 m-auto">
        <form action="login.php" method="POST">
            <img class="mb-4" src="transporte.png" alt="" width="100" height="70">
            <h1 class="h3 mb-3 fw-normal">Bienvenido</h1>
            <label>Nombre de usuario:</label><br>
            <input type="text" name="nombre_usuario" required><br><br>
            <label>Contraseña:</label><br>
            <input type="password" name="contrasenya" required><br><br>
            <button type="submit" name="login">Entrar</button>
        </form>
        <p>¿No tienes cuenta? <a href="usuarios/index.php">Regístrate</a></p>
</body>
</html>