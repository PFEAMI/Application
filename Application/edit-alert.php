<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$alert_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $pdo->prepare("SELECT * FROM alerts WHERE id = ?");
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

} catch (PDOException $e) {
    die("Erreur: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];
    $status = isset($_POST['status']) ? $_POST['status'] : $alert['status'];

    try {
        $stmt = $pdo->prepare("UPDATE alerts SET title = ?, description = ?, priority = ?, status = ? WHERE id = ?");
        $stmt->execute([$title, $description, $priority, $status, $alert_id]);

        $redirect = ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'dashboard.php';
        header("Location: $redirect?success=alert_updated");
        exit();
    } catch (PDOException $e) {
        die("Erreur: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Alerte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Modifier l'alerte</h2>
        <form method="POST">
            <div class="form-group">
                <label for="title">Titre:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($alert['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($alert['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="priority">Priorité:</label>
                <select id="priority" name="priority">
                    <option value="low" <?= $alert['priority'] === 'low' ? 'selected' : '' ?>>Faible</option>
                    <option value="medium" <?= $alert['priority'] === 'medium' ? 'selected' : '' ?>>Moyenne</option>
                    <option value="high" <?= $alert['priority'] === 'high' ? 'selected' : '' ?>>Haute</option>
                </select>
            </div>
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="form-group">
                <label for="status">Statut:</label>
                <select id="status" name="status">
                    <option value="active" <?= $alert['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="resolved" <?= $alert['status'] === 'resolved' ? 'selected' : '' ?>>Résolue</option>
                    <option value="archived" <?= $alert['status'] === 'archived' ? 'selected' : '' ?>>Archivée</option>
                </select>
            </div>
            <?php endif; ?>
            <button type="submit" class="btn">Enregistrer</button>
            <a href="<?= ($_SESSION['role'] === 'admin') ? 'admin_dashboard.php' : 'dashboard.php' ?>" class="btn btn-cancel">Annuler</a>
        </form>
    </div>
</body>
</html>
