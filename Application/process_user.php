<?php
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données du formulaire
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = 'user';  // Par défaut, on donne le rôle 'user' à tout nouvel utilisateur
    $created_at = date('Y-m-d H:i:s'); // Date actuelle

    // Vérifier si l'email existe déjà dans la base de données
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $emailExists = $stmt->fetchColumn();

    if ($emailExists) {
        // Si l'email existe déjà, on met le message d'erreur dans la session et on redirige vers create_user.php
        $_SESSION['error_message'] = "❌ L'email est déjà utilisé. Veuillez en choisir un autre.";
        header("Location: create_user.php");
        exit();
    }

    // Hachage du mot de passe pour plus de sécurité
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, $created_at, $role]);

        // Rediriger vers le tableau de bord avec un message de succès
        header("Location: dashboard.php?user=success");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la création de l'utilisateur : " . $e->getMessage());
    }
}
?>

