<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            die("Cet email est déjà utilisé.");
        }

        // Insérer le nouvel utilisateur
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password]);

        // Récupérer l'ID du nouvel utilisateur
        $user_id = $pdo->lastInsertId();

        // Démarrer la session et enregistrer les informations de l'utilisateur
        session_start();
        $_SESSION['user_id'] = $user_id;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email; // Ajout de l'email dans la session si nécessaire
        $_SESSION['role'] = 'user'; // Par défaut, l'utilisateur est un 'user'. Vous pouvez le changer si nécessaire

        // Rediriger l'utilisateur vers le tableau de bord après inscription
        header("Location: dashboard.php");
        exit();

    } catch (PDOException $e) {
        die("Erreur lors de l'inscription: " . $e->getMessage());
    }
}
?>
