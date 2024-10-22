<?php
session_start();
require 'db.php'; // Include the database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first-name']);
    $last_name = trim($_POST['last-name']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $Rpassword = $_POST['rpassword'];

    // Vérifier que tous les champs sont remplis
    if (empty($first_name) || empty($last_name) || empty($email) || empty($username) || empty($password) || empty($Rpassword)) {
        $error = "Veuillez remplir tous les champs.";
    }
    // Vérifier que les deux mots de passe correspondent
    elseif ($password !== $Rpassword) {
        $error = "Les mots de passe ne correspondent pas.";
    }
    // Vérifier si l'utilisateur existe déjà
    else {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        $user = $stmt->fetch();

        if ($user) {
            $error = "Ce nom d'utilisateur ou cet e-mail est déjà utilisé.";
        } else {
            // Hacher le mot de passe avant de l'enregistrer
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur dans la base de données
            $stmt = $pdo->prepare('INSERT INTO users (first_name, last_name, email, username, password) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$first_name, $last_name, $email, $username, $hashed_password]);

            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $userId = $stmt->fetchColumn(); // Récupérer l'ID de l'utilisateur
            
            $stmt = $pdo->prepare("INSERT INTO education (user_id) 
                                   VALUES (?)");
            $stmt->execute([$userId]);

            $stmt = $pdo->prepare("INSERT INTO experiences (user_id) 
                                   VALUES (?)");
            $stmt->execute([$userId]);

            $stmt = $pdo->prepare("INSERT INTO skills (user_id) 
                                   VALUES (?)");
            $stmt->execute([$userId]);

            $stmt = $pdo->prepare("INSERT INTO skills (user_id) 
                                   VALUES (?)");
            $stmt->execute([$userId]);

            $stmt = $pdo->prepare("INSERT INTO projects (user_id) 
                                   VALUES (?)");
            $stmt->execute([$userId]);
            // Démarrer une session pour l'utilisateur nouvellement inscrit
            $_SESSION['username'] = $username;

            // Rediriger vers une page de succès (ou la page de login)
            header("Location: index.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html >
<html lang="en" class="h-full bg-black">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="h-full">
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
  <div class="relative px-6 py-12 ml-4 mr-8 bg-gray-800 text-white text-sm text-center rounded-md">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img class="mx-auto h-10 w-auto" src="https://support.ynov.com/hc/theming_assets/01HZP9F20X0889CZX2V6ZJH3Z5" alt="Your Company">
    <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-white">Register to your account</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
    <form class="space-y-6" action="#" method="POST" class="mx-auto mt-16 max-w-xl sm:mt-20">
    <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
        <div>
            <label for="first-name" class="block text-sm font-medium leading-6 text-white">First name</label>
            <div class="mt-2.5">
                <input type="text" name="first-name" id="first-name" autocomplete="given-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
        <div>
            <label for="last-name" class="block text-sm font-medium leading-6 text-white">Last name</label>
            <div class="mt-2.5">
                <input type="text" name="last-name" id="last-name" autocomplete="family-name" class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
        <div class="sm:col-span-2">
            <label for="email" class="block text-sm font-medium leading-6 text-white">email</label>
            <div class="mt-2">
            <input id="email" name="email" type="mail" required class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
        <div class="sm:col-span-2">
            <label for="username" class="block text-sm font-medium leading-6 text-white">username</label>
            <div class="mt-2">
            <input id="username" name="username" type="text" required class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
        <div class="sm:col-span-2">
            <label for="password" class="block text-sm font-medium leading-6 text-white">Password</label>
            <div class="mt-2">
            <input id="password" name="password" type="password" required class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>

        <div class="sm:col-span-2">
            <label for="rpassword" class="block text-sm font-medium leading-6 text-white">Repeat Password</label>
            <div class="mt-2">
            <input id="rpassword" name="rpassword" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </div>
        </div>
    </div>
      <div>
        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Register</button>
      </div>
    </form>
  </div>
  </div>
</div>
</body>
</html>
