<?php
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id'];

    try {
        // Vous devrez créer une table 'alerts' dans votre base de données
        $stmt = $pdo->prepare("INSERT INTO alerts (title, description, priority, user_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$title, $description, $priority, $user_id]);

        header("Location: dashboard.php?alert=success");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la création de l'alerte: " . $e->getMessage());
    }
}
?>
