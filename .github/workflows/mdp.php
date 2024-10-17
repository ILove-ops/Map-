<?php
$servername = "mysql-sevenoenzo.alwaysdata.net";
$username = "327462_admin"; 
$password = "enzo5657"; 
$dbname = "sevenoenzo_admin"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Prépare la requête d'insertion pour les mots de passe
$stmt = $conn->prepare("INSERT INTO utilisateurs (nom_utilisateur, mot_de_passe) VALUES (?, ?)");
$stmt->bind_param("ss", $nom_utilisateur, $mot_de_passe_hache);

$nom_utilisateur = $_POST['username'];
$mot_de_passe = $_POST['password'];

// Hache le mot de passe
$mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

if ($stmt->execute()) {
    echo "L'utilisateur a été enregistré avec succès.";
} else {
    echo "Erreur : " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
