<?php
// Initialisation des variables pour stocker les erreurs
$name = $email = $message = '';
$nameErr = $emailErr = $messageErr = '';

// Vérification des données du formulaire lorsque celui-ci est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation du nom
    if (empty($_POST['name'])) {
        $nameErr = "Le nom est requis";
    } else {
        $name = htmlspecialchars($_POST['name']);
    }

    // Validation de l'email
    if (empty($_POST['email'])) {
        $emailErr = "L'email est requis";
    } else {
        $email = htmlspecialchars($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Format d'email invalide";
        }
    }

    // Validation du message
    if (empty($_POST['message'])) {
        $messageErr = "Le message est requis";
    } else {
        $message = htmlspecialchars($_POST['message']);
    }

    // Si toutes les validations sont réussies, envoyer l'email
    if (empty($nameErr) && empty($emailErr) && empty($messageErr)) {
        $to = "destinataire@example.com"; // Adresse email du destinataire
        $subject = "Message de $name via le formulaire de contact";
        $body = "Nom: $name\nEmail: $email\nMessage:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            echo "Message envoyé avec succès!";
        } else {
            echo "Erreur lors de l'envoi du message.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de contact</title>
</head>
<body>
    <h2>Contactez-nous</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="name">Nom:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $name; ?>"><br>
        <span style="color:red;"><?php echo $nameErr; ?></span><br>

        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>"><br>
        <span style="color:red;"><?php echo $emailErr; ?></span><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message"><?php echo $message; ?></textarea><br>
        <span style="color:red;"><?php echo $messageErr; ?></span><br>

        <input type="submit" value="Envoyer">
    </form>
</body>
</html>
