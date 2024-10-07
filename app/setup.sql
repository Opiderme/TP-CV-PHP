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

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
);

INSERT INTO users (username, password, first_name, last_name, email) 
VALUES ('opiderme', '$2y$10$ybOP3hulir7vLGAC4A8xUe9nAEAVnGZHsPWcdo7.EWUANkcKwFVLi', 'opi', 'derme', 'opiderme@gmail.com');