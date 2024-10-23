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

try {
    // Récupérer tous les utilisateurs et leurs informations de CV
    $stmt = $pdo->query('
        SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.linkedin, u.github, u.job_title, u.profile_description,
               e.company_name, e.job_title as job_title_exp, e.start_date as start_date_exp, e.end_date as end_date_exp, e.description as description_exp,
               ed.degree, ed.institution, ed.start_date as start_date_edu, ed.end_date as end_date_edu, ed.description as description_edu,
               s.skill_name, s.skill_type,
               p.project_name, p.description as description_proj
        FROM users u
        LEFT JOIN experiences e ON u.id = e.user_id
        LEFT JOIN education ed ON u.id = ed.user_id
        LEFT JOIN skills s ON u.id = s.user_id
        LEFT JOIN projects p ON u.id = p.user_id
    ');
    
    $cvs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
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
                <a href="cv.php" class="text-sm font-semibold leading-6 text-white-900 z-50">Your CV</a>
                <a href="#" class="text-sm font-semibold leading-6 text-white-900 z-50">Projets</a>
                <a href="#" class="text-sm font-semibold leading-6 text-white-900 z-50">Contact</a>
                <a href="#" class="text-sm font-semibold leading-6 text-white-900 z-50">Profile</a>
            </div>
            <div class="lg:flex lg:flex-1 lg:justify-end">
                <p class="text-sm font-semibold leading-6 text-white-900 z-50"><?php echo $personalInfo['first_name']; ?></p>
                <p class="text-sm font-semibold leading-6 text-white-900 z-50"><?php echo $personalInfo['last_name']; ?></p>
                <?php if ($isAdmin): ?>
                    <a href="logout.php" class="text-sm font-semibold leading-6 text-white-900 z-50">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="text-sm font-semibold leading-6 text-white-900 z-50">Log in <span aria-hidden="true">&rarr;</span></a>
                    <a href="register.php" class="text-sm font-semibold leading-6 text-white-900 z-50">register <span aria-hidden="true">&rarr;</span></a>

                <?php endif; ?>                
            </div>
            </nav>
            </div>
        </header>
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80" aria-hidden="true">
            <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
            </div>
            <!---------------------------------------------------------------->
            <div class="container mx-auto p-6">
                <h1 class="text-4xl font-bold text-white text-center mb-8">Liste des CVs</h1>

                <?php foreach ($cvs as $cv): ?>
                    <div class="max-w-4xl mx-auto p-6 bg-[#7584C7]/30 text-white backdrop-blur-sm shadow-md rounded-lg mt-10">
                        <h2 class="text-2xl font-bold"><?= htmlspecialchars($cv['first_name'] ?? '' . ' ' . $cv['last_name'] ?? '') ?></h2>
                        <p><?= htmlspecialchars($cv['job_title'] ?? '') ?></p>
                        <p><?= htmlspecialchars($cv['email'] ?? '') ?> | <?= htmlspecialchars($cv['phone'] ?? '') ?></p>

                        <?php if ($cv['linkedin']): ?>
                            <a href="<?= htmlspecialchars($cv['linkedin'] ?? '') ?>" class="underline">LinkedIn</a> |
                        <?php endif; ?>
                        <?php if ($cv['github'] ?? ''): ?>
                            <a href="<?= htmlspecialchars($cv['github'] ?? '') ?>" class="underline">GitHub</a>
                        <?php endif; ?>

                        <p class="mt-4"><strong>Description :</strong> <?= htmlspecialchars($cv['profile_description'] ?? '') ?></p>

                        <h3 class="text-xl font-semibold mt-6">Expériences Professionnelles</h3>
                        <ul class="list-disc ml-6">
                            <li>
                                <strong><?= htmlspecialchars($cv['company_name'] ?? '') ?></strong> | <?= htmlspecialchars($cv['job_title_exp'] ?? '') ?>
                                (<?= htmlspecialchars($cv['start_date_exp'] ?? '') ?> - <?= htmlspecialchars($cv['end_date_exp'] ?? '') ?>)
                                <p><?= htmlspecialchars($cv['description_exp'] ?? '') ?></p>
                            </li>
                        </ul>

                        <h3 class="text-xl font-semibold mt-6">Éducation</h3>
                        <ul class="list-disc ml-6">
                            <li>
                                <strong><?= htmlspecialchars($cv['degree'] ?? '') ?></strong> - <?= htmlspecialchars($cv['institution'] ?? '') ?>
                                (<?= htmlspecialchars($cv['start_date_edu'] ?? '') ?> - <?= htmlspecialchars($cv['end_date_edu'] ?? '') ?>)
                                <p><?= htmlspecialchars($cv['description_edu'] ?? '') ?></p>
                            </li>
                        </ul>

                        <h3 class="text-xl font-semibold mt-6">Compétences</h3>
                        <ul class="list-disc ml-6">
                            <li>
                                <?= htmlspecialchars($cv['skill_name'] ?? '') ?> (<?= htmlspecialchars($cv['skill_type'] ?? '') ?>)
                            </li>
                        </ul>

                        <h3 class="text-xl font-semibold mt-6">Projets</h3>
                        <ul class="list-disc ml-6">
                            <li>
                                <strong><?= htmlspecialchars($cv['project_name'] ?? '') ?></strong>
                                <pw><?= htmlspecialchars($cv['description_proj'] ?? '') ?></p>
                            </li>
                        </ul>
                    </div>
                <?php endforeach; ?>

            </div>
            <!---------------------------------------------------------------->
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
