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

$userId = $_SESSION['user_id'];

try {
    // Récupérer les informations personnelles de l'utilisateur
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, linkedin, github, job_title, profile_description FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les informations d'éducation
    $stmt = $pdo->prepare("SELECT degree, institution, start_date, end_date, description FROM education WHERE user_id = ?");
    $stmt->execute([$userId]);
    $educationInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les expériences professionnelles
    $stmt = $pdo->prepare("SELECT company_name, job_title, start_date, end_date, description FROM experiences WHERE user_id = ?");
    $stmt->execute([$userId]);
    $experienceInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les compétences
    $stmt = $pdo->prepare("SELECT skill_name, skill_type FROM skills WHERE user_id = ?");
    $stmt->execute([$userId]);
    $skillsInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //var_dump($skillsInfo);
    //exit;

    // Récupérer les projets
    $stmt = $pdo->prepare("SELECT project_name, description FROM projects WHERE user_id = ?");
    $stmt->execute([$userId]);
    $projectsInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    echo "Error fetching CV information: " . $e->getMessage();
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
<body class="bg-black text-white">
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
                <!-- Contenu principal -->
                <div class="container mx-auto p-8 text-center">
                    <h1 class="text-3xl font-bold mb-6">Welcome to My Website</h1>
                    <p class="mb-6">This is a website where you can fill in your CV details.</p>
                    
                    <!-- Bouton pour ouvrir le formulaire -->
                    <button id="openModalBtn" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        Open CV Form
                    </button>
                </div>

                <!-- Modal Overlay -->
                <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40"></div>

                <!-- Formulaire modal -->
                <div id="modal" class="fixed inset-0 flex items-center justify-center hidden z-50">
                    <div class="bg-gray-800 text-white rounded-lg shadow-lg p-8 max-w-2xl w-full relative modal-content">
                        <button id="closeModalBtn" class="absolute top-4 right-4 text-gray-400 hover:text-white focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Titre du formulaire -->
                        <h2 class="text-2xl font-semibold mb-4">Fill in your CV Details</h2>

                        <!-- Formulaire CV -->
                        <form action="submit_cv.php" method="POST" class="space-y-6">
                            <!-- User Personal Information -->
                            <h3 class="text-xl font-semibold mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium">First Name</label>
                                    <input type="text" name="first_name" id="first_name" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($userInfo['first_name'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="last_name" class="block text-sm font-medium">Last Name</label>
                                    <input type="text" name="last_name" id="last_name" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($userInfo['last_name'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input type="email" name="email" id="email" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($userInfo['email'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium">Phone</label>
                                    <input type="tel" name="phone" id="phone" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($userInfo['phone'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="linkedin" class="block text-sm font-medium">LinkedIn</label>
                                    <input type="url" name="linkedin" id="linkedin" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white " value="<?php echo htmlspecialchars($userInfo['linkedin'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="github" class="block text-sm font-medium">GitHub</label>
                                    <input type="url" name="github" id="github" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($userInfo['github'] ?? ''); ?>">
                                </div>
                                <div class="col-span-2">
                                    <label for="job_title" class="block text-sm font-medium">Job Title</label>
                                    <input type="text" name="job_title" id="job_title" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($userInfo['job_title'] ?? ''); ?>">
                                </div>
                                <div class="col-span-2">
                                    <label for="profile_description" class="block text-sm font-medium">Profile Description</label>
                                    <textarea name="profile_description" id="profile_description" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white"><?= htmlspecialchars($userInfo['profile_description'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Qualifications and Work Experience 
                            <h3 class="text-xl font-semibold mb-4">Qualifications & Work Experience</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="education" class="block text-sm font-medium">Highest Qualification</label>
                                    <input type="text" name="education" id="education" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" >
                                </div>
                                <div>
                                    <label for="school" class="block text-sm font-medium">Institution</label>
                                    <input type="text" name="school" id="school" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="">
                                </div>
                                <div class="col-span-2">
                                    <label for="experience" class="block text-sm font-medium">Work Experience</label>
                                    <textarea name="experience" id="experience" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="></textarea>
                                </div>
                            </div>-->

                            <!-- Experience Section -->
                            <h3 class="text-xl font-semibold mt-8 mb-4">Work Experience</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="company_name" class="block text-sm font-medium">Company Name</label>
                                    <input type="text" name="company_name[]" id="company_name" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($experienceInfo[0]['company_name']  ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="job_title" class="block text-sm font-medium">Job Title</label>
                                    <input type="text" name="job_title[]" id="job_title" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($experienceInfo[0]['job_title'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="start_date" class="block text-sm font-medium">Start Date</label>
                                    <input type="date" name="start_date[]" id="start_date" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($experienceInfo[0]['start_date'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium">End Date</label>
                                    <input type="date" name="end_date[]" id="end_date" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($experienceInfo[0]['end_date'] ?? ''); ?>">
                                </div>
                                <div class="col-span-2">
                                    <label for="experience_description" class="block text-sm font-medium">Description</label>
                                    <textarea name="experience_description[]" id="experience_description" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white"><?= htmlspecialchars($experienceInfo[0]['description'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Skills Section -->
                            <h3 class="text-xl font-semibold mt-8 mb-4">Skills</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="skill_name" class="block text-sm font-medium">Skill</label>
                                    <input type="text" name="skill_name[]" id="skill_name" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($skillsInfo[0]['skill_name'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="skill_type" class="block text-sm font-medium">Type</label>
                                    <select name="skill_type[]" id="skill_type" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white">
                                        <option value="technical">Technical</option>
                                        <option value="soft">Soft</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Education Section -->
                            <h3 class="text-xl font-semibold mt-8 mb-4">Education</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="degree" class="block text-sm font-medium">Degree</label>
                                    <input type="text" name="degree[]" id="degree" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($educationInfo[0]['degree'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="institution" class="block text-sm font-medium">Institution</label>
                                    <input type="text" name="institution[]" id="institution" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($educationInfo[0]['institution'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="education_start_date" class="block text-sm font-medium">Start Date</label>
                                    <input type="date" name="education_start_date[]" id="education_start_date" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($educationInfo[0]['start_date'] ?? ''); ?>">
                                </div>
                                <div>
                                    <label for="education_end_date" class="block text-sm font-medium">End Date</label>
                                    <input type="date" name="education_end_date[]" id="education_end_date" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($educationInfo[0]['end_date'] ?? ''); ?>">
                                </div>
                                <div class="col-span-2">
                                    <label for="education_description" class="block text-sm font-medium">Description</label>
                                    <textarea name="education_description[]" id="education_description" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white"><?= htmlspecialchars($educationInfo[0]['description'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Projects Section -->
                            <h3 class="text-xl font-semibold mt-8 mb-4">Projects</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="project_name" class="block text-sm font-medium">Project Name</label>
                                    <input type="text" name="project_name[]" id="project_name" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white" value="<?php echo htmlspecialchars($projectsInfo[0]['project_name'] ?? ''); ?>">
                                </div>
                                <div class="col-span-2">
                                    <label for="project_description" class="block text-sm font-medium">Description</label>
                                    <textarea name="project_description[]" id="project_description" class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-md focus:ring-2 focus:ring-indigo-500 text-white"><?= htmlspecialchars($projectsInfo[0]['description'] ?? '') ?></textarea>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-6">
                                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600">
                                    Submit CV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="max-w-4xl mx-auto p-6 bg-[#7584C7]/30 text-white backdrop-blur-sm shadow-md rounded-lg mt-10">
        <h1 class="text-4xl font-bold text-center text-blue-600 mb-10">Mon CV</h1>

        <!-- Informations personnelles -->
        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-[#C2CDFF] border-b-2 border-blue-600 mb-4">Informations personnelles</h2>
            <ul class="space-y-2 text-[#C2CDFF]">
                <li><strong>Nom :</strong> <?php echo htmlspecialchars($userInfo['first_name'] . " " . $userInfo['last_name']); ?></li>
                <li><strong>Email :</strong> <?php echo htmlspecialchars($userInfo['email']); ?></li>
                <li><strong>Téléphone :</strong> <?php echo htmlspecialchars($userInfo['phone']); ?></li>
                <li><strong>LinkedIn :</strong> <?php echo htmlspecialchars($userInfo['linkedin']); ?></li>
                <li><strong>GitHub :</strong> <?php echo htmlspecialchars($userInfo['github']); ?></li>
                <li><strong>Job Title :</strong> <?php echo htmlspecialchars($userInfo['job_title']); ?></li>
                <li><strong>Profile Description :</strong> <?php echo htmlspecialchars($userInfo['profile_description']); ?></li>
            </ul>
        </div>

        <!-- Éducation -->
        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-[#C2CDFF] border-b-2 border-blue-600 mb-4">Éducation</h2>
            <?php if (!empty($educationInfo)) : ?>
                <ul class="space-y-4 text-[#C2CDFF]">
                    <?php foreach ($educationInfo as $education) : ?>
                        <li>
                            <strong><?php echo htmlspecialchars($education['degree']); ?></strong> - 
                            <?php echo htmlspecialchars($education['institution']); ?><br>
                            <span class="text-sm text-[#C2CDFF]">
                                <?php echo htmlspecialchars($education['start_date']); ?> - 
                                <?php echo htmlspecialchars($education['end_date']); ?>
                            </span><br>
                            <p class="mt-2"><?php echo htmlspecialchars($education['description']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Aucune information d'éducation disponible.</p>
            <?php endif; ?>
        </div>

        <!-- Expériences professionnelles -->
        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-[#C2CDFF] border-b-2 border-blue-600 mb-4">Expériences professionnelles</h2>
            <?php if (!empty($experienceInfo)) : ?>
                <ul class="space-y-4 text-[#C2CDFF]">
                    <?php foreach ($experienceInfo as $experience) : ?>
                        <li>
                            <strong><?php echo htmlspecialchars($experience['job_title']); ?></strong> - 
                            <?php echo htmlspecialchars($experience['company_name']); ?><br>
                            <span class="text-sm text-[#C2CDFF]">
                                <?php echo htmlspecialchars($experience['start_date']); ?> - 
                                <?php echo htmlspecialchars($experience['end_date']); ?>
                            </span><br>
                            <p class="mt-2"><?php echo htmlspecialchars($experience['description']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Aucune expérience professionnelle disponible.</p>
            <?php endif; ?>
        </div>

        <!-- Compétences -->
        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-[#C2CDFF] border-b-2 border-blue-600 mb-4">Compétences</h2>
            <?php if (!empty($skillsInfo)) : ?>
                <ul class="space-y-2 text-[#C2CDFF]">
                    <?php foreach ($skillsInfo as $skill) : ?>
                        <li>
                            <strong><?php echo htmlspecialchars($skill['skill_name']); ?></strong> - 
                            <?php echo htmlspecialchars($skill['skill_type']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Aucune compétence disponible.</p>
            <?php endif; ?>
        </div>

        <!-- Projets -->
        <div class="mb-10">
            <h2 class="text-2xl font-semibold text-[#C2CDFF] border-b-2 border-blue-600 mb-4">Projets</h2>
            <?php if (!empty($projectsInfo)) : ?>
                <ul class="space-y-4 text-[#C2CDFF]">
                    <?php foreach ($projectsInfo as $project) : ?>
                        <li>
                            <strong><?php echo htmlspecialchars($project['project_name']); ?></strong><br>
                            <p class="mt-2"><?php echo htmlspecialchars($project['description']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <p>Aucun projet disponible.</p>
            <?php endif; ?>
        </div>
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
    <!-- Script JavaScript pour gérer le modal -->
    <script>
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const modal = document.getElementById('modal');
        const overlay = document.getElementById('modalOverlay');
        const body = document.body;

        openModalBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
            overlay.classList.remove('hidden');
            body.classList.add('modal-active');
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            overlay.classList.add('hidden');
            body.classList.remove('modal-active');
        });

        // Ferme le modal si on clique à l'extérieur
        overlay.addEventListener('click', () => {
            modal.classList.add('hidden');
            overlay.classList.add('hidden');
            body.classList.remove('modal-active');
        });
    </script>
</body>
</html>
