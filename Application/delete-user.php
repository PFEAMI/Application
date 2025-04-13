<?php
include 'config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=access_denied");
    exit();
}

// Vérifier si l'ID de l'utilisateur est passé dans l'URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Vérifier si l'utilisateur existe avant de supprimer
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Supprimer l'utilisateur
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: admin_dashboard.php?success=Utilisateur supprimé avec succès.");
        } else {
            echo "<p class='error'>Utilisateur non trouvé.</p>";
        }
    } catch (PDOException $e) {
        echo "<p class='error'>Erreur: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p class='error'>ID d'utilisateur manquant.</p>";
}
?>

