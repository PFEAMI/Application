<?php 
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer une alerte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Créer une nouvelle alerte</h2>
        <form action="process_alert.php" method="post">
            <div class="form-group">
                <label for="title">Titre:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="priority">Priorité:</label>
                <select id="priority" name="priority">
                    <option value="low">Faible</option>
                    <option value="medium">Moyenne</option>
                    <option value="high">Haute</option>
                </select>
	    </div>
	    <div class="form-group">
                <label for="telephone">Numéro de téléphone :</label>
                <input type="tel" id="telephone" name="telephone" placeholder="ex : 55 000 000" required>
            </div>

            <button type="submit" class="btn">Créer l'alerte</button>
        </form>
        <a href="dashboard.php" class="btn">Retour au tableau de bord</a>
    </div>
</body>
</html>
