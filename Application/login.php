<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - Mon Application</title>
    
    <!-- Intégration AdminLTE -->
    <link rel="stylesheet" href="assets/AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/AdminLTE/dist/css/adminlte.min.css">
    
    <!-- Votre CSS personnalisé -->
    <link rel="stylesheet" href="/home/vagrant/data_to_sync/Application/css/style.css">
    
    <style>
        /* Styles spécifiques à la page de login */
        .login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            animation: gradientBG 15s ease infinite;
            background-size: 400% 400%;
        }
        .login-box {
            width: 360px;
            margin: 0 auto;
        }
        .login-logo {
            text-align: absolute;
	    top: 200px;
	    left: 20px;
	    z-index: 1000;
        }
        .login-logo img {
            height: 100px;
        }
        .login-card {
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
        }
        .login-card-body {
            padding: 30px;
        }
        .success-message {
            color: #28a745;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="login-page">
<div class="login-box">
    <!-- Logo -->
    <div class="login-logo">
        <img src="assets/images/logo.png" alt="Logo Mon Application">
    </div>
    
    <!-- Carte de connexion -->
    <div class="card login-card">
        <div class="card-body login-card-body">
            <h3 class="login-box-msg">Connectez-vous à votre compte</h3>
            
            <?php if (isset($_GET['signup']) && $_GET['signup'] === 'success'): ?>
                <p class="success-message">Inscription réussie ! Veuillez vous connecter.</p>
            <?php endif; ?>
            
            <form action="process_login.php" method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt mr-2"></i> Se connecter
                        </button>
                    </div>
                </div>
            </form>
            
            <p class="mt-3 mb-1 text-center">
                <a href="signup.php" class="text-primary">
                    <i class="fas fa-user-plus mr-1"></i> Créer un compte
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Scripts AdminLTE -->
<script src="assets/AdminLTE/plugins/jquery/jquery.min.js"></script>
<script src="assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/AdminLTE/dist/js/adminlte.min.js"></script>

<!-- Animation simple -->
<script>
$(document).ready(function(){
    $('.login-card').hide().fadeIn(500);
});
</script>
</body>
</html>
