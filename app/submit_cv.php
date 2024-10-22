<?php
// Inclure la connexion à la base de données
require 'db.php';

// Démarrer la session pour accéder à $_SESSION
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Démarrer la transaction
        $pdo->beginTransaction();

        // Récupérer l'ID de l'utilisateur à partir de la session
        $userId = $_SESSION['user_id'];

        // Récupérer les informations du formulaire
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $linkedin = $_POST['linkedin'];
        $github = $_POST['github'];
        $job_title = implode(',', $_POST['job_title']);
        $profile_description = $_POST['profile_description'];


        // Mettre à jour les informations personnelles
        $stmt = $pdo->prepare('UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, linkedin = ?, github = ?, job_title = ?, profile_description = ? WHERE id = ?');
        $stmt->execute([$first_name, $last_name, $email, $phone, $linkedin, $github, $job_title, $profile_description, $userId]);

        // Insertion des diplômes/éducation
        foreach ($_POST['degree'] as $index => $degree) {
            $institution = $_POST['institution'][$index];
            $education_start_date = $_POST['education_start_date'][$index];
            $education_end_date = $_POST['education_end_date'][$index];
            $education_description = $_POST['education_description'][$index];
            
            $stmt = $pdo->prepare("UPDATE education 
                       SET degree = ?, institution = ?, start_date = ?, end_date = ?, description = ? 
                       WHERE user_id = ?");
            $stmt->execute([$degree, $institution, $education_start_date, $education_end_date, $education_description, $userId]);
        }

        // Insertion des expériences professionnelles
        foreach ($_POST['company_name'] as $index => $company_name) {
            $job_title = $_POST['job_title'][$index];
            $start_date = $_POST['start_date'][$index];
            $end_date = $_POST['end_date'][$index];
            $experience_description = $_POST['experience_description'][$index];
            
            $stmt = $pdo->prepare("UPDATE experiences 
                       SET company_name = ?, job_title = ?, start_date = ?, end_date = ?, description = ? 
                       WHERE user_id = ?");
            $stmt->execute([$company_name, $job_title, $start_date, $end_date, $experience_description, $userId]);
        }

        // Insertion des compétences
        foreach ($_POST['skill_name'] as $index => $skill_name) {
            $skill_type = $_POST['skill_type'][$index];
            
            $stmt = $pdo->prepare("UPDATE skills 
                       SET skill_name = ?, skill_type = ? 
                       WHERE user_id = ?");
            $stmt->execute([$skill_name, $skill_type, $userId]);
        }

        // Insertion des projets
        foreach ($_POST['project_name'] as $index => $project_name) {
            $project_description = $_POST['project_description'][$index];
            
            $stmt = $pdo->prepare("UPDATE projects 
                       SET project_name = ?, description = ? 
                       WHERE user_id = ?");
            $stmt->execute([$project_name, $project_description, $userId]);
        }

        // Valider la transaction
        $pdo->commit();

        // Redirection après la soumission
        header("Location: cv.php");
        exit;

    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}
?>
