<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mobility Alliance</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('../img/taxi.jpg');
      color: #333;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    /* Botón de salir arriba a la derecha */
    .logout-btn {
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #e74c3c;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: bold;
      font-size: 16px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }

    .container {
      background-color: white;
      padding: 20px 40px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 600px;
    }

    h1 {
      text-align: center;
      color: #000;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }

    input[type="number"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }

    .result {
      background-color: #ecf0f1;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
      font-size: 18px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .result span {
      font-weight: bold;
    }

    .result a {
      background-color: #3498db;
      color: white;
      padding: 5px 10px;
      border-radius: 3px;
      text-decoration: none;
      font-size: 14px;
      font-weight: bold;
    }

    .result a:hover {
      background-color: #2980b9;
    }

    button {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      background-color: #2980b9;
    }
  </style>
</head>

<body>

  <!-- Botón de salir -->
  <a href="../logout.php" class="logout-btn">Salir</a>
  
  <div class="container">
    <h1>Bienvenido a Mobility Alliance</h1>
    <p style="text-align: center;">¿A dondé desea usted viajar?</p>

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
      res.innerHTML += `<div class="result"><span>Bolt: ${bolt.toFixed(2)} €</span> <a href="https://bolt.eu/es/" target="_blank">Reservar</a></div>`;
      res.innerHTML += `<div class="result"><span>Taxi (día y entre semana): ${taxiDia.toFixed(2)} €</span></div>`;
      res.innerHTML += `<div class="result"><span>Taxi (noche, fines y festivos): ${taxiNoche.toFixed(2)} €</span></div>`;
      if (taxiAeroDia !== null) {
        res.innerHTML += `<div class="result"><span>Taxi desde aeropuerto (día): ${taxiAeroDia.toFixed(2)} €</span></div>`;
        res.innerHTML += `<div class="result"><span>Taxi desde aeropuerto (noche, fines y festivos): ${taxiAeroNoche.toFixed(2)} €</span></div>`;
      }
    }
  </script>

</body>

</html>