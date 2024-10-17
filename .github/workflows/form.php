<form action="insere.php" method="post">
           <label for="name">Nom de la salle :</label>
           <input type="text" id="name" name="name" required>
           <br><br>
           <label for="id">Id du capteur (1-100) :</label>
           <input type="number" id="id" name="id" min="1" max="100" required>
           <br><br>
           <label for="latitude">Latitude :</label>
           <input type="text" id="latitude" name="latitude" required>
           <br>
           <label for="longitude">Longitude :</label>
           <input type="text" id="longitude" name="longitude" required>
           <br>
           <label for="date">Date :</label>
           <br>
           <input type="text" id="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
           <br><br>
           <input type="button" value="Localisation auto" onclick="getLocation()">
           <input type="button" value="Afficher la position" onclick="showMarker()">
           <br><br>
           <label for="mesure">Valeur de la mesure :</label>
           <input type="number" id="mesure" name="mesure" min="-50" max="50" required>
           <br><br>
           <input type="submit" value="Envoyer les données">
       </form>

       <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
       <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

       <h2>Localisation des mesures</h2>
       <div id="mapid" style="width: 600px; height: 300px;"></div>

       <script type="text/javascript">
   const apiKey = 'f220c9614a4d2debecc5e85175f8d71d';

   var map = L.map('mapid').setView([47.305, -0.06], 6);
   L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
       attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
   }).addTo(map);

   var markers = [];

   // Fonction pour charger les marqueurs depuis la base de données
   function loadMarkersFromDB() {
       fetch('get_markers.php')
           .then(response => response.json())
           .then(data => {
               data.forEach(markerData => {
                   addMarker(markerData.latitude, markerData.longitude, markerData.mesure, markerData.nom_salle, markerData.date);
               });
           })
           .catch(error => console.error('Erreur lors du chargement des marqueurs:', error));
   }

   function getLocation() {
       if (navigator.geolocation) {
           navigator.geolocation.getCurrentPosition(function (position) {
               var lat = position.coords.latitude;
               var lon = position.coords.longitude;

               document.getElementById("latitude").value = lat;
               document.getElementById("longitude").value = lon;
           });
       } else {
           alert("La géolocalisation n'est pas supportée par ce navigateur.");
       }
   }

   function showMarker() {
       var lat = document.getElementById("latitude").value;
       var lon = document.getElementById("longitude").value;
       var mesure = document.getElementById("mesure").value;
       var name = document.getElementById("name").value;
       var date = document.getElementById("date").value;

       if (lat && lon) {
           addMarker(lat, lon, mesure, name, date);
       } else {
           alert("Veuillez entrer une latitude et une longitude valides.");
       }
   }

   function fetchWeather(lat, lon, callback) {
       const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=fr`;

       fetch(url)
           .then(response => response.json())
           .then(data => {
               const weatherInfo = {
                   temperature: data.main.temp,
                   description: data.weather[0].description,
                   humidity: data.main.humidity,
                   windSpeed: data.wind.speed,
               };
               callback(weatherInfo);
           })
           .catch(error => {
               alert("Erreur lors de la récupération des données météo : " + error);
           });
   }

   function addMarker(lat, lon, mesure, name, date, saveToLocal = true) {
       var marker = L.marker([lat, lon]).addTo(map);
       markers.push(marker);

       fetchWeather(lat, lon, function (weather) {
           var popupContent = `<strong>Nom de la salle :</strong> ${name}<br>
                               <strong>Date :</strong> ${date}<br>
                               <strong>Température mesurée :</strong> ${mesure}°C<br>
                               <strong>Météo actuelle :</strong> ${weather.description}, ${weather.temperature}°C<br>
                               <strong>Humidité :</strong> ${weather.humidity}%<br>
                               <strong>Vitesse du vent :</strong> ${weather.windSpeed} m/s`;
           marker.bindPopup(popupContent).openPopup();
       });
   }

   window.onload = loadMarkersFromDB;
</script>
   </center>
</body>

</html>
