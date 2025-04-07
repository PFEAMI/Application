<?php
// Configuration de la base de données
define('DB_HOST', '192.168.56.11');
define('DB_PORT', '5432');
define('DB_NAME', 'ma_base_de_donnees');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'azerty123');

// Connexion à PostgreSQL
try {
    $dsn = "pgsql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Initialisation de la session
session_start();
?>
