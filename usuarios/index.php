<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de usuario</title>
  <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <h2>Formulario de registro</h2>

  <form action="crear.php" method="POST">

    <label>Nombre de usuario:</label><br>
    <input type="text" name="nombre_usuario" required><br><br>

    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Apellidos:</label><br>
    <input type="text" name="apellidos" required><br><br>

    <label>Domicilio:</label><br>
    <input type="text" name="domicilio"><br><br>

    <label>Correo electrónico:</label><br>
    <input type="email" name="correo_electronico" required><br><br>

    <label>Teléfono:</label><br>
    <input type="number" name="telefono"><br><br>

    <label>Contraseña:</label><br>
    <input type="password" name="contrasenya" required><br><br>

    <button type="submit">Registrar</button>
  </form>

</body>
</html>

