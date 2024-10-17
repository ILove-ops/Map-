<?php
$servername = "";
$username = ""; 
$password = ""; 
$dbname = ""; 


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}


$stmt = $conn->prepare("INSERT INTO tmesures (nom_salle, id_capteur, latitude, longitude, valeur) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("siddi", $nom_salle, $id_capteur, $latitude, $longitude, $valeur);


$nom_salle = $_POST['name'];
$id_capteur = $_POST['id'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$valeur = $_POST['mesure'];
$date_heure = $_POST['date'];

if ($stmt->execute()) {
    echo "Les données sont insérées avec succès.";
} else {
    echo "Erreur : " . $stmt->error;
}

$stmt->close();
$conn->close();

?>

