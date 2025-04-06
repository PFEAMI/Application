<?php
// Vérifie si le formulaire a été soumis (si tu veux ajouter un formulaire)
if (isset($_POST['name'])) {
    $name = htmlspecialchars($_POST['name']);
    $message = "Bonjour, $name ! Bienvenue sur notre site.";
} else {
    $message = "Veuillez entrer votre nom ci-dessous.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Application Web</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            text-align: center;
            padding: 50px;
        }
        .container {
            margin-top: 30px;
        }
        h1 {
            color: #4CAF50;
        }
        #message {
            font-size: 1.5em;
            color: #333;
            margin-top: 20px;
        }
        form input {
            padding: 10px;
            font-size: 1em;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #45a049;
        }
        #changeTextButton {
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        #changeTextButton:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur mon application web !</h1>
        <p id="message"><?= $message ?></p> <!-- Affiche le message dynamique -->

        <!-- Formulaire pour entrer le nom -->
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Entrez votre nom" required>
            <button type="submit">Envoyer</button>
        </form>

        <!-- Bouton pour changer le texte -->
	<button id="changeTextButton">Cliquez pour changer le texte</button>
        <!-- Lien vers la page de contact -->
	<a href="contact.php">Contactez-nous</a>
        <!-- Lien vers la page de contact -->
        <a href="numero.php">Numero suivant</a>

    </div>

    <script>
        // JavaScript pour changer le texte et modifier le bouton
        document.getElementById('changeTextButton').addEventListener('click', function() {
            const messageElement = document.getElementById('message');
            const buttonElement = document.getElementById('changeTextButton');

            if (messageElement.innerHTML === 'Veuillez entrer votre nom ci-dessous.') {
                // Si le message est celui par défaut (avant soumission du nom), on ne fait rien
                alert('Veuillez d\'abord entrer votre nom dans le formulaire.');
            } else {
                // Change le texte et l'apparence du bouton si le message a été personnalisé
                if (messageElement.innerHTML === 'Bonjour, bienvenue sur notre site !') {
                    messageElement.innerHTML = 'Le texte a été changé avec succès !';
                    buttonElement.innerHTML = 'Restaurer le texte';
                    buttonElement.style.backgroundColor = '#FF6347'; // Change la couleur du bouton
                } else {
                    messageElement.innerHTML = 'Bonjour, bienvenue sur notre site !';
                    buttonElement.innerHTML = 'Cliquez pour changer le texte';
                    buttonElement.style.backgroundColor = '#4CAF50'; // Retourne à la couleur d\'origine
                }
            }
        });
    </script>
</body>
</html>
