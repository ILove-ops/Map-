<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form action="login.php" method="post">
        <label for="username">Identifiant :</label><br>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>



<?php
// Paramètres de connexion à la base de données
$host = 'localhost'; // ou l'adresse de votre serveur
$dbname = 'votre_base_de_donnees';
$username = 'votre_utilisateur';
$password = 'votre_mot_de_passe';

try {
    // Création de la connexion
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérification des données du formulaire
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Préparer et exécuter la requête
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE username = :username");
        $stmt->bindParam(':username', $user);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Vérification du mot de passe
        if ($result && password_verify($pass, $result['password'])) {
            echo "Connexion réussie !";
            // Ici, vous pouvez démarrer une session ou rediriger l'utilisateur
        } else {
            echo "Identifiant ou mot de passe incorrect.";
        }
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>


CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);



