<?php
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Vérification spéciale pour l'admin (mot de passe en clair dans la base)
            if ($user['role'] === 'admin' && $password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: admin_dashboard.php");
                exit();
            }
            // Pour les autres utilisateurs (mot de passe hashé)
            elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                header("Location: login.php?error=invalid_password");
                exit();
            }
        } else {
            header("Location: login.php?error=user_not_found");
            exit();
        }
    } catch (PDOException $e) {
        die("Erreur de connexion: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit();
}
?>
