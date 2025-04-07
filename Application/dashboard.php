<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Empêcher les admins d'accéder à cette page
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin_dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <h3>Vos alertes</h3>
        <?php
        try {
            $stmt = $pdo->prepare("SELECT * FROM alerts WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($alerts) {
                echo "<table class='user-table'>";
                echo "<tr><th>Titre</th><th>Description</th><th>Priorité</th><th>Date</th><th>Actions</th></tr>";
                foreach ($alerts as $alert) {
                    echo "<tr>";
                    echo "<td>".htmlspecialchars($alert['title'])."</td>";
                    echo "<td>".htmlspecialchars($alert['description'])."</td>";
                    echo "<td>".ucfirst($alert['priority'])."</td>";
                    echo "<td>".$alert['created_at']."</td>";
                    echo "<td class='actions'>";
                    echo "<a href='edit_alert.php?id=".$alert['id']."' class='btn btn-small'>Modifier</a>";
                    echo "<a href='delete_alert.php?id=".$alert['id']."' class='btn btn-small btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette alerte ?\")'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Vous n'avez aucune alerte</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Erreur: ".$e->getMessage()."</p>";
        }

        // Afficher les messages de succès/erreur
        if (isset($_GET['success'])) {
            echo "<p class='success'>".htmlspecialchars($_GET['success'])."</p>";
        }
        if (isset($_GET['error'])) {
            echo "<p class='error'>".htmlspecialchars($_GET['error'])."</p>";
        }
        ?>

        <div class="user-actions">
            <a href="create-alert.php" class="btn">Créer une alerte</a>
            <a href="logout.php" class="btn btn-logout">Déconnexion</a>
        </div>
    </div>
</body>
</html>
