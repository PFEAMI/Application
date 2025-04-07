<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
            <p class="success">Inscription réussie ! Veuillez vous connecter Merci beaucoup.</p>
        <?php endif; ?>
        <form action="process_login.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas encore de compte? <a href="signup.php">Inscrivez-vous ici</a></p>
    </div>
</body>
</html>
