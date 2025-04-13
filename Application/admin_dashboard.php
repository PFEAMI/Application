<?php
include 'config.php';

// Vérifier si l'utilisateur est admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php?error=access_denied");
    exit();
}

// Gestion du filtre de priorité
$filter = isset($_GET['priority']) ? $_GET['priority'] : 'all';

// Requête pour les alertes
$sql = "SELECT a.id, a.title, a.description, a.priority, a.status, u.username, a.created_at
        FROM alerts a
        JOIN users u ON a.user_id = u.id";

if ($filter != 'all') {
    $sql .= " WHERE a.priority = :priority";
}

$sql .= " ORDER BY
            CASE a.priority
                WHEN 'high' THEN 1
                WHEN 'medium' THEN 2
                WHEN 'low' THEN 3
            END,
            a.created_at ASC";

$stmt = $pdo->prepare($sql);
if ($filter != 'all') {
    $stmt->bindParam(':priority', $filter);
}
$stmt->execute();
$alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Requête pour les utilisateurs
try {
    $users_stmt = $pdo->query("SELECT id, username, email, role, created_at FROM users");
    $users = $users_stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $users_error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord Admin</title>
    
    <!-- AdminLTE -->
    <link rel="stylesheet" href="assets/AdminLTE/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="assets/AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/AdminLTE/dist/css/adminlte.min.css">
    
    <style>
        .priority-high { color: #dc3545; font-weight: bold; }
        .priority-medium { color: #fd7e14; }
        .priority-low { color: #28a745; }
        .admin-only { border-left: 3px solid #dc3545; }
        .filter-active { background-color: #007bff !important; color: white !important; }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-user-shield"></i> <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="logout.php" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </a>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="admin_dashboard.php" class="brand-link">
            <img src="assets/images/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Admin Panel</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-shield img-circle elevation-2" style="font-size: 2rem; color: #dc3545;"></i>
                </div>
                <div class="info">
                    <a href="#" class="d-block"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    <span class="text-danger"><small>Administrateur</small></span>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="admin_dashboard.php" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Tableau de bord</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="edit-user.php" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>Gérer les utilisateurs</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="create-alert.php" class="nav-link">
                            <i class="nav-icon fas fa-plus-circle"></i>
                            <p>Créer une alerte</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="create_user.php" class="nav-link">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>Créer utilisateur</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <h1>Tableau de bord Administrateur</h1>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Admin</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Messages de statut -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo htmlspecialchars($_GET['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Statistiques -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <?php
                                $users_count = count($users);
                                ?>
                                <h3><?php echo $users_count; ?></h3>
                                <p>Utilisateurs</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="edit-user.php" class="small-box-footer">
                                Gérer <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <?php
                                $alerts_count = count($alerts);
                                ?>
                                <h3><?php echo $alerts_count; ?></h3>
                                <p>Alertes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <a href="#alerts-section" class="small-box-footer">
                                Voir <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Gestion des utilisateurs -->
                <div class="card admin-only">
                    <div class="card-header">
                        <h3 class="card-title">Gestion des utilisateurs</h3>
                        <div class="card-tools">
                            <a href="create_user.php" class="btn btn-sm btn-success">
                                <i class="fas fa-user-plus"></i> Nouvel utilisateur
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if (isset($users_error)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($users_error); ?>
                            </div>
                        <?php elseif (!empty($users)): ?>
                            <table id="usersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Date création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo ucfirst($user['role']); ?></td>
                                            <td><?php echo $user['created_at']; ?></td>
                                            <td class="text-center">
                                                <a href="edit-user.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                                <a href="delete-user.php?id=<?php echo $user['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <?php else: ?>
                                                <span class="badge bg-info">Compte actuel</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Aucun utilisateur trouvé.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Gestion des alertes -->
                <div class="card admin-only mt-4" id="alerts-section">
                    <div class="card-header">
                        <h3 class="card-title">Gestion des alertes</h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <a href="?priority=all" class="btn btn-sm <?= $filter == 'all' ? 'btn-primary' : 'btn-default' ?>">Toutes</a>
                                <a href="?priority=high" class="btn btn-sm <?= $filter == 'high' ? 'btn-primary' : 'btn-default' ?>">High</a>
                                <a href="?priority=medium" class="btn btn-sm <?= $filter == 'medium' ? 'btn-primary' : 'btn-default' ?>">Medium</a>
                                <a href="?priority=low" class="btn btn-sm <?= $filter == 'low' ? 'btn-primary' : 'btn-default' ?>">Low</a>
                            </div>
                            <a href="create-alert.php" class="btn btn-sm btn-success ml-2">
                                <i class="fas fa-plus"></i> Nouvelle alerte
                            </a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if (!empty($alerts)): ?>
                            <table id="alertsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Description</th>
                                        <th>Priorité</th>
                                        <th>Statut</th>
                                        <th>Utilisateur</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alerts as $alert): ?>
                                        <tr>
                                            <td><?php echo $alert['id']; ?></td>
                                            <td><?php echo htmlspecialchars($alert['title']); ?></td>
                                            <td><?php echo htmlspecialchars($alert['description']); ?></td>
                                            <td>
                                                <?php 
                                                $priority_class = '';
                                                switch ($alert['priority']) {
                                                    case 'high': $priority_class = 'priority-high'; break;
                                                    case 'medium': $priority_class = 'priority-medium'; break;
                                                    case 'low': $priority_class = 'priority-low'; break;
                                                }
                                                ?>
                                                <span class="<?php echo $priority_class; ?>">
                                                    <?php echo ucfirst($alert['priority']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo ucfirst($alert['status']); ?></td>
                                            <td><?php echo htmlspecialchars($alert['username']); ?></td>
                                            <td><?php echo $alert['created_at']; ?></td>
                                            <td class="text-center">
                                                <a href="edit-alert.php?id=<?php echo $alert['id']; ?>" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete_alert.php?id=<?php echo $alert['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Aucune alerte trouvée.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>Admin Panel - Version <?php echo date('Y'); ?></strong>
    </footer>
</div>

<!-- Scripts -->
<script src="assets/AdminLTE/plugins/jquery/jquery.min.js"></script>
<script src="assets/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/AdminLTE/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="assets/AdminLTE/dist/js/adminlte.min.js"></script>

<script>
$(function () {
    // Initialisation DataTables
    $('#usersTable, #alertsTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        }
    });
});
</script>
</body>
</html>
