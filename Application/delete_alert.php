<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$alert_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Vérifier que l'alerte existe
    $stmt = $pdo->prepare("SELECT user_id FROM alerts WHERE id = ?");
    $stmt->execute([$alert_id]);
    $alert = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$alert) {
        header("Location: dashboard.php?error=alert_not_found");
        exit();
    }

    // Vérifier les permissions
    if ($_SESSION['role'] !== 'admin' && $alert['user_id'] !== $_SESSION['user_id']) {
        header("Location: dashboard.php?error=no_permission");
        exit();
    }

    // Supprimer l'alerte
    $stmt = $pdo->prepare("DELETE FROM alerts WHERE id = ?");
    $stmt->execute([$alert_id]);

    $redirect = ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'dashboard.php';
    header("Location: $redirect?success=alert_deleted");
    exit();

} catch (PDOException $e) {
    die("Erreur: " . $e->getMessage());
}
?>
