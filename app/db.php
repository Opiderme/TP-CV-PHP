<?php
// Paramètres de connexion à la base de données
$host = 'db';  // Nom du service dans docker-compose.yml
$db = 'cv_db';
$user = 'root'; // Nom d'utilisateur MySQL
$pass = 'root'; // Mot de passe MySQL
$charset = 'utf8mb4';

// Data Source Name (DSN)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options pour PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Mécanisme de retry (réessai) pour se connecter à la base de données
$maxRetries = 5;   // Nombre maximum de tentatives
$retry = 0;        // Compteur de tentatives
$connected = false; // Variable pour suivre la connexion

while ($retry < $maxRetries && !$connected) {
    try {
        // Tentative de connexion à la base de données
        $pdo = new PDO($dsn, $user, $pass, $options);
        $connected = true;
    } catch (\PDOException $e) {
        // Si la connexion échoue, augmenter le compteur et réessayer
        $retry++;
        echo "Tentative de connexion échouée, essai $retry sur $maxRetries. Erreur : " . $e->getMessage() . "\n";
        sleep(3); // Attendre 3 secondes avant de réessayer
    }
}

// Si après plusieurs tentatives, la connexion n'a toujours pas réussi
if (!$connected) {
    throw new \PDOException("Impossible de se connecter à la base de données après $maxRetries tentatives.");
}
