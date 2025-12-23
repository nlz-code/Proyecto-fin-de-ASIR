<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header('Location: ../index.php');
    exit;
}

require_once('../config.php');
require_once('../db_pdo.php');

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="wDNIth=device-wDNIth, initial-scale=1.0">
    <title>Registro</title>
    <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        main {
            margin: 40px;
        }
    </style>
</head>

<body>
    <main>
        <h1>Registro de usuarios</h1>
        <form action="<?php echo isset($usuario) ? 'actualizar.php' : 'crear.php' ?>" method="post">
            <div class="row">
                <div class="col-md">
                    <label>DNI</label>
                    <input class="form-control" name="DNI" readonly value="<?php echo isset($usuario) ? $usuario['DNI'] : '' ?>">
                </div>
                <div class="col-md">
                    <label>Nombre</label>
                    <input class="form-control" name="nombre" required value="<?php echo isset($usuario) ? $usuario['nombre'] : '' ?>">
                </div>
                <div class="col-md">
                    <label>Apellidos</label>
                    <input class="form-control" name="Apellidos" type="Apellidos" required value="<?php echo isset($usuario) ? $usuario['Apellidos'] : '' ?>">
                </div>
                <div class="col-md">
                    <label>Gmail</label>
                    <input class="form-control" name="Gmail" required value="<?php echo isset($usuario) ? $usuario['Gmail'] : '' ?>">
                </div>
                <div class="col-md">
                    <label>Domicilio</label>
                    <input class="form-control" name="Domicilio" required value="<?php echo isset($usuario) ? $usuario['Domicilio'] : '' ?>">
                </div>
                <div class="col-md">
                    <label>Contraseña</label>
                    <input class="form-control" name="Contraseña" required value="<?php echo isset($usuario) ? $usuario['Contraseña'] : '' ?>">
                </div>
            </div>
            <input class="btn btn-success btn-sm" type="submit" value="Guardar">
        </form>
</body>

</html>