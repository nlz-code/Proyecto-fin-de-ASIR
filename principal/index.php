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
    <p class="intro-text">¿A dónde quieres viajar hoy?</p>

    <label for="km">¿Cuántos kilómetros quieres recorrer?</label>
    <input type="number" id="km" placeholder="Introduce los kilómetros" min="0.01" required>

    <label for="min">¿Cuántos minutos dura el viaje?</label>
    <input type="number" id="min" placeholder="Introduce los minutos" min="1" required>

    <button onclick="calcularTarifas()">Calcular Tarifas</button>

    <!-- Botón estrella para guardar favorito (se muestra después de calcular) -->
    <div id="favoritoContainer" class="favorito-container">
      <button type="button" class="btn btn-warning btn-sm" onclick="abrirModalFavorito()">
        <i class="fas fa-star"></i> Guardar como favorito
      </button>
    </div>

    <div id="resultados"></div>
  </div>

  <!-- Modal para guardar favorito -->
  <div class="modal fade" id="modalFavorito" tabindex="-1" aria-labelledby="modalFavoritoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalFavoritoLabel">Guardar viaje como favorito</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <label for="nombreFavorito">Nombre del favorito:</label>
          <input type="text" id="nombreFavorito" class="form-control" placeholder="Ej: Viaje al trabajo" required>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="guardarFavorito()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let kmActual = 0;
    let minActual = 0;
    const modal = new bootstrap.Modal(document.getElementById('modalFavorito'));

    function calcularTarifas() {
      const km = parseFloat(document.getElementById('km').value);
      const min = parseInt(document.getElementById('min').value);
      const res = document.getElementById('resultados');
      res.innerHTML = '';

      if (isNaN(km) || isNaN(min) || km <= 0 || min <= 0) {
        res.innerHTML = "<p class='error-message'>Por favor, ingresa valores válidos para kilómetros y minutos.</p>";
        document.getElementById('favoritoContainer').style.display = 'none';
        return;
      }

      kmActual = km;
      minActual = min;

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

      // Mostrar botón para guardar favorito
      document.getElementById('favoritoContainer').style.display = 'block';
    }

    function abrirModalFavorito() {
      document.getElementById('nombreFavorito').value = '';
      modal.show();
    }

    function guardarFavorito() {
      const nombre = document.getElementById('nombreFavorito').value.trim();

      if (!nombre) {
        alert('Por favor, ingresa un nombre para el favorito');
        return;
      }

      // Enviar datos al servidor
      fetch('./viajes_favoritos/guardar_favorito.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'nombre=' + encodeURIComponent(nombre) + '&km=' + kmActual + '&min=' + minActual
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('¡Favorito guardado correctamente!');
            modal.hide();
          } else {
            alert('Error al guardar el favorito: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error al guardar el favorito');
        });
    }

    window.addEventListener('load', function() {
      const kmFavorito = sessionStorage.getItem('kmFavorito');
      const minFavorito = sessionStorage.getItem('minFavorito');

      if (kmFavorito && minFavorito) {
        document.getElementById('km').value = kmFavorito;
        document.getElementById('min').value = minFavorito;
        calcularTarifas();
        sessionStorage.removeItem('kmFavorito');
        sessionStorage.removeItem('minFavorito');
      }
    });
  </script>

</body>

</html>
