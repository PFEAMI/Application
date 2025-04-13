<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Afficher le message d'erreur s'il existe
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
if ($error_message) {
    echo '<div class="alert error">' . $error_message . '</div>';
    unset($_SESSION['error_message']); // Supprimer le message d'erreur après affichage
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un utilisateur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Créer un utilisateur</h2>
        <form action="process_user.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
	    </div>
            <button type="submit" class="btn">Créer l'utilisateur</button>
        </form>
        <a href="dashboard.php" class="btn">Retour au tableau de bord</a>
    </div>
</body>
</html>
