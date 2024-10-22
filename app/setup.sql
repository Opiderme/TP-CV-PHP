-- Create database
CREATE DATABASE cv_db;

-- Use the database
USE cv_db;

-- Create table for personal information
CREATE TABLE IF NOT EXISTS personal_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    title VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    profile_description TEXT NOT NULL
);

-- Insert default data
INSERT INTO personal_info (name, title, email, phone, profile_description) 
VALUES ('John Doe', 'Web Developer | Programmer | Tech Enthusiast', 'johndoe@example.com', '(123) 456-7890', 'I am a passionate web developer with experience in creating dynamic websites and applications.');

-- Create a table for admin login credentials
CREATE TABLE IF NOT EXISTS admins (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Insert an admin user (the password should be hashed using PHP's password_hash('password123', PASSWORD_DEFAULT) function)
INSERT INTO admins (username, password) 
VALUES ('admin', '$2y$10$ybOP3hulir7vLGAC4A8xUe9nAEAVnGZHsPWcdo7.EWUANkcKwFVLi'); -- Password: password123

/*CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
);*/

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    linkedin VARCHAR(255),
    github VARCHAR(255),
    job_title VARCHAR(255), -- Titre du CV, ex: "Développeur Full Stack"
    profile_description TEXT -- Profil professionnel (description brève du parcours)
);


INSERT INTO users (username, password, first_name, last_name, email) 
VALUES ('opiderme', '$2y$10$ybOP3hulir7vLGAC4A8xUe9nAEAVnGZHsPWcdo7.EWUANkcKwFVLi', 'opi', 'derme', 'opiderme@gmail.com');

CREATE TABLE experiences (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT, -- Relation avec la table 'users'
    company_name VARCHAR(255), -- Nom de l'entreprise
    job_title VARCHAR(255), -- Intitulé du poste
    start_date DATE, -- Date de début de l'emploi
    end_date DATE, -- Date de fin, NULL si toujours en poste
    description TEXT, -- Description des missions et réalisations
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT, -- Relation avec la table 'users'
    skill_name VARCHAR(255), -- Nom de la compétence (ex: "HTML", "Leadership")
    skill_type ENUM('technical', 'soft') DEFAULT 'technical', -- Type de compétence
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE education (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT, -- Relation avec la table 'users'
    degree VARCHAR(255), -- Diplôme (ex: "Master en Informatique")
    institution VARCHAR(255), -- Nom de l'établissement
    start_date DATE, -- Date de début
    end_date DATE, -- Date de fin
    description TEXT, -- Réalisations ou distinctions
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT, -- Relation avec la table 'users'
    project_name VARCHAR(255), -- Nom du projet
    description TEXT, -- Brève description du projet
    result TEXT, -- Résultats obtenus (ex: "augmentation de 10% du trafic")
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE certifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT, -- Relation avec la table 'users'
    certification_name VARCHAR(255), -- Nom de la certification (ex: "Certification AWS")
    institution VARCHAR(255), -- Nom de l'institution ou de l'organisme de certification
    date_obtained DATE, -- Date à laquelle la certification a été obtenue
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
