<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mobility Alliance</title>
  <link href="../bootstrap-5.3.8-dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/principal.css" rel="stylesheet">
</head>

<body>
  <!-- Barra de navegación -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Mobility Alliance</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="./index.php">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="./viajes_favoritos/index.php">Viajes favoritos</a></li>
          <li class="nav-item"><a class="nav-link" href="./contactos/index.php">Contacto</a></li>
          <li class="nav-item"><a class="nav-link" href="./perfil/index.php">Mi perfíl</a></li>
          <li class="nav-item"><a class="nav-link" href="../login/logout.php">Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1>Bienvenido a </h1>
    <h1>Mobility Alliance</h1>
    <p style="text-align: center;">¿A dónde quieres viajar hoy?</p>

    <label for="km">¿Cuántos kilómetros quieres recorrer?</label>
    <input type="number" id="km" placeholder="Introduce los kilómetros" min="0.01" required>

    <label for="min">¿Cuántos minutos dura el viaje?</label>
    <input type="number" id="min" placeholder="Introduce los minutos" min="1" required>

    <button onclick="calcularTarifas()">Calcular Tarifas</button>

    <div id="resultados"></div>
  </div>

  <script>
    function calcularTarifas() {
      const km = parseFloat(document.getElementById('km').value);
      const min = parseInt(document.getElementById('min').value);
      const res = document.getElementById('resultados');
      res.innerHTML = '';

      if (isNaN(km) || isNaN(min) || km <= 0 || min <= 0) {
        res.innerHTML = "<p style='color:red;'>Por favor, ingresa valores válidos para kilómetros y minutos.</p>";
        return;
      }

      const uber = km * 1.2 + min * 0.1;
      const cabify = km > 20 ? (20 * 1.65 + (km - 20) * 1.1) : (km * 1.65);
      const bolt = km * 1.3 + min * 0.13;

      let taxiDia = km <= 12 ? (km * 0.96 + 1.71) : (km * (0.68 * 2));
      let taxiNoche = km <= 12 ? (km * 1.16 + 2.1) : (km * (0.8 * 2));
      let taxiAeroDia = km > 12 ? (km * (0.68 * 2) + 6) : null;
      let taxiAeroNoche = km > 12 ? (km * (0.8 * 2) + 6) : null;

      res.innerHTML += `<div class="result"><span>Uber: ${uber.toFixed(2)} €</span> <a href="https://www.uber.com/es/" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Cabify: ${cabify.toFixed(2)} €</span> <a href="https://cabify.com/es" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Bolt: ${bolt.toFixed(2)} €</span> <a href="https://bolt.eu/es-es/" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi (día y entre semana): ${taxiDia.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi (noche, fines y festivos): ${taxiNoche.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      if (taxiAeroDia !== null) {
        res.innerHTML += `<div class="result"><span>Taxi desde aeropuerto (día): ${taxiAeroDia.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
        res.innerHTML += `<div class="result"><span>Taxi desde aeropuerto (noche, fines y festivos): ${taxiAeroNoche.toFixed(2)} €</span><a href="./taxistas/index.php" target="_blank">Contactar</a></div>`;
      }
    }
  </script>

</body>

</html>