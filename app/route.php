<?php
// Extraire uniquement le chemin de l'URL pour le routage
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple système de routage
switch ($uri) {
    case '/':
        require 'index.php';
        break;
    case '/login':
        require 'login.php';
        break;
    case '/logout':
        require 'logout.php';
        break;
    case '/register':
        require 'register.php';
        break;
    case '/cv':
        require 'cv.php';
        break;
    case '/projets':
        require 'projets.php';
        break;
    default:
        http_response_code(404);
        echo "404 - Page not found";
        break;
}
