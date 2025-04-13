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

    // Récupérer les informations de l'utilisateur
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo "<p class='error'>Utilisateur non trouvé.</p>";
            exit();
        }
    } catch (PDOException $e) {
        echo "<p class='error'>Erreur: " . $e->getMessage() . "</p>";
        exit();
    }
} else {
    echo "<p class='error'>ID d'utilisateur manquant.</p>";
    exit();
}

// Modifier les informations de l'utilisateur si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Mettre à jour les informations dans la base de données
    try {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: admin_dashboard.php?success=Utilisateur modifié avec succès.");
        exit();
    } catch (PDOException $e) {
        echo "<p class='error'>Erreur: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Utilisateur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Modifier l'utilisateur</h2>

        <!-- Formulaire de modification de l'utilisateur -->
        <form action="edit-user.php?id=<?php echo $user['id']; ?>" method="POST">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="role">Rôle</label>
            <select name="role" id="role" required>
                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>Utilisateur</option>
            </select>

            <button type="submit" class="btn">Sauvegarder</button>
        </form>

        <!-- Lien pour revenir au tableau de bord -->
        <a href="admin_dashboard.php" class="btn">Retour</a>
    </div>
</body>
</html>
