<?php
include 'config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=access_denied");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Bienvenue Admin, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

        <h3>Gestion des utilisateurs</h3>
        <?php
        try {
            $stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($users) {
                echo "<table class='admin-table'>";
                echo "<tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Date création</th><th>Actions</th></tr>";
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>".$user['id']."</td>";
                    echo "<td>".htmlspecialchars($user['username'])."</td>";
                    echo "<td>".htmlspecialchars($user['email'])."</td>";
                    echo "<td>".ucfirst($user['role'])."</td>";
                    echo "<td>".$user['created_at']."</td>";
                    echo "<td class='actions'>";
                    if ($user['id'] != $_SESSION['user_id']) {
                        echo "<a href='edit_user.php?id=".$user['id']."' class='btn btn-small'>Modifier</a>";
                        echo "<a href='delete_user.php?id=".$user['id']."' class='btn btn-small btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cet utilisateur ?\")'>Supprimer</a>";
                    } else {
                        echo "<span>Compte actuel</span>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucun utilisateur trouvé</p>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Erreur: ".$e->getMessage()."</p>";
        }
        ?>

        <h3>Gestion des alertes</h3>
        <?php
        try {
            $stmt = $pdo->query("SELECT a.id, a.title, a.description, a.priority, a.status, u.username, a.created_at 
                                FROM alerts a JOIN users u ON a.user_id = u.id");
            $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($alerts) {
                echo "<table class='admin-table'>";
                echo "<tr><th>ID</th><th>Titre</th><th>Description</th><th>Priorité</th><th>Statut</th><th>Utilisateur</th><th>Date</th><th>Actions</th></tr>";
                foreach ($alerts as $alert) {
                    echo "<tr>";
                    echo "<td>".$alert['id']."</td>";
                    echo "<td>".htmlspecialchars($alert['title'])."</td>";
                    echo "<td>".htmlspecialchars($alert['description'])."</td>";
                    echo "<td>".ucfirst($alert['priority'])."</td>";
                    echo "<td>".ucfirst($alert['status'])."</td>";
                    echo "<td>".htmlspecialchars($alert['username'])."</td>";
                    echo "<td>".$alert['created_at']."</td>";
                    echo "<td class='actions'>";
                    echo "<a href='edit_alert.php?id=".$alert['id']."' class='btn btn-small'>Modifier</a>";
                    echo "<a href='delete_alert.php?id=".$alert['id']."' class='btn btn-small btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer cette alerte ?\")'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>Aucune alerte trouvée</p>";
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

        <div class="admin-actions">
            <a href="create_user.php" class="btn">Créer un utilisateur</a>
            <a href="create-alert.php" class="btn">Créer une alerte</a>
            <a href="logout.php" class="btn btn-logout">Déconnexion</a>
        </div>
    </div>
</body>
</html>
