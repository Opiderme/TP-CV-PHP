<?php
session_start();

require 'db.php'; // Inclure la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit;
}

// Récupérer l'ID de l'utilisateur connecté depuis la session
$userId = $_SESSION['user_id'];

// Vérifier si l'utilisateur est un administrateur
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'];

// Récupérer les informations personnelles de l'utilisateur connecté depuis la base de données
$stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userId]);
$personalInfo = $stmt->fetch();

// Si aucune information n'est trouvée, rediriger ou afficher un message d'erreur
if (!$personalInfo) {
    echo "Utilisateur non trouvé.";
    exit;
}

// Handle form submission and update the database (admin only)
if ($_SERVER["REQUEST_METHOD"] == "POST" && $isAdmin) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    // Update personal information in the database
    $stmt = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ? WHERE id = 1');
    $stmt->execute([$first_name, $last_name]);

    // Redirect to the CV page to reflect the changes
    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Curriculum Vitae</title>
    <link href="./output.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Style pour empêcher le scrolling en arrière-plan quand le modal est ouvert */
        .modal-active {
            overflow: hidden;
        }

        /* Assure que le modal est scrollable */
        .modal-content {
            max-height: 90vh; /* Limite la hauteur à 90% de la fenêtre */
            overflow-y: auto; /* Ajoute le scroll si nécessaire */
        }
    </style>
</head>
<body class="bg-black">
    <?php
        // Personal information variables
        //$name = "John Doe";
        //$title = "Web Developer | Programmer | Tech Enthusiast";
        //$email = "johndoe@example.com";
        //$phone = "(123) 456-7890";
        //$profileDescription = "I am a passionate web developer with experience in creating dynamic websites and applications. Skilled in HTML, CSS, JavaScript, and backend technologies like Node.js. Looking forward to contributing my skills to a dynamic team.";
    ?>

    <div class="bg-black">
        <!-- Header Section -->
        <header class="absolute bg-gray-800 text-white text-sm inset-x-0">
            <!--<p>Ynov - Curriculum Vitae</p>-->
            <nav class="flex items-center justify-between p-6 lg:px-8 z-50" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="#" class="-m-1.5 p-1.5">
                <span class="sr-only">CurrYNOV Vitae</span>
                <img class="h-8 w-auto" src="https://support.ynov.com/hc/theming_assets/01HZP9F20X0889CZX2V6ZJH3Z5" alt="">
                </a>
            </div>
            <div class="lg:flex lg:gap-x-12">
                <a href="/cv" class="text-sm font-semibold leading-6 text-white-900 z-50">Your CV</a>
                <a href="/projets" class="text-sm font-semibold leading-6 text-white-900 z-50">Projets</a>
                <a href="#" class="text-sm font-semibold leading-6 text-white-900 z-50">Contact</a>
                <a href="#" class="text-sm font-semibold leading-6 text-white-900 z-50">Profile</a>
            </div>
            <div class="lg:flex lg:flex-1 lg:justify-end">
                <p class="relative text-sm font-semibold leading-6 text-white-900 z-50 right-2"><?= htmlspecialchars($personalInfo['first_name']. ' ' . $personalInfo['last_name']) ?></p>
                <?php if ($isAdmin): ?>
                    <a href="/logout" class="text-sm font-semibold leading-6 text-white-900 z-50">Logout</a>
                <?php else: ?>
                    <a href="/login" class="text-sm font-semibold leading-6 text-white-900 z-50">Log in <span aria-hidden="true">&rarr;</span></a>
                    <a href="/register" class="text-sm font-semibold leading-6 text-white-900 z-50">register <span aria-hidden="true">&rarr;</span></a>

                <?php endif; ?>                
            </div>
            </nav>
            </div>
        </header>
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
            <div class="text-center">
                <h1 class="text-balance text-4xl font-bold tracking-tight text-[#FFD700] sm:text-6xl">YOUR CV BUT BETTER WITH US</h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat fugiat aliqua.</p>
                <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="#" class="rounded-md bg-[#C8AA00] px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get started</a>
                <a href="#" class="text-sm font-semibold leading-6 text-[#FFD700]">Learn more <span aria-hidden="true">→</span></a>
                </div>
            </div>
            </div>
            <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-50rem)]" aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
        </div>
        <footer>
            <div class="px-6 py-12 bg-gray-800 text-white text-sm text-center">
                <p>&copy; 2022 Your Company. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>
</html>
