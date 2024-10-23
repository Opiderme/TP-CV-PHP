# TP-CV-PHP                                                                
                                                                 

## Description

This project is a **CV Management System** built with **PHP** and **Tailwind CSS**. Users can create, update, and view their CVs, including personal information, work experience, education, skills, and projects. Additionally, other users can view all CVs in a list.

### Features
- User login and session management
- Add, update, and display personal information
- Manage education, work experience, skills, and projects
- View all CVs from all users on a public page
- Modern design with Tailwind CSS

## Table of Contents
- [Installation](#installation)
- [Usage](#usage)
- [Technologies](#technologies)
- [Database Structure](#database-structure)
- [Contributing](#contributing)
- [License](#license)

## Installation

To run this project locally, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/Opiderme/TP-CV-PHP.git

2. Navigate into the project directory:
    ```bash
    cd TP-CV-PHP/Docker

3. Run command to start sproject:
    ```bash
    docker-compose up
    ```
    - if you don't have it, install Docker !

4. Set up the database:
   - Go to the database in adress 127.0.0.1:8080, connect to with login and password "root" :
     ```bash
     127.0.0.1:8080
     user : root
     password : root
     ```
   - Go to "Requête SQL" and run this sql query:
     ```SQL
    CREATE TABLE IF NOT EXISTS personal_info (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        title VARCHAR(150) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        profile_description TEXT NOT NULL
    );

    INSERT INTO personal_info (name, title, email, phone, profile_description) 
    VALUES ('John Doe', 'Web Developer | Programmer | Tech Enthusiast', 'johndoe@example.com', '(123) 456-7890', 'I am a passionate web developer with experience in creating dynamic websites and applications.');

    CREATE TABLE IF NOT EXISTS admins (
        id INT PRIMARY KEY AUTO_INCREMENT,
        username VARCHAR(50) NOT NULL,
        password VARCHAR(255) NOT NULL
    );

    INSERT INTO admins (username, password) 
    VALUES ('admin', '$2y$10$ybOP3hulir7vLGAC4A8xUe9nAEAVnGZHsPWcdo7.EWUANkcKwFVLi');

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
    job_title VARCHAR(255),
    profile_description TEXT
    );


    INSERT INTO users (username, password, first_name, last_name, email) 
    VALUES ('opiderme', '$2y$10$ybOP3hulir7vLGAC4A8xUe9nAEAVnGZHsPWcdo7.EWUANkcKwFVLi', 'opi', 'derme', 'opiderme@gmail.com');

    CREATE TABLE experiences (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        company_name VARCHAR(255),
        job_title VARCHAR(255),
        start_date DATE,
        end_date DATE,
        description TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE skills (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        skill_name VARCHAR(255),
        skill_type ENUM('technical', 'soft') DEFAULT 'technical',
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE education (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        degree VARCHAR(255),
        institution VARCHAR(255),
        start_date DATE,
        end_date DATE,
        description TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE projects (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        project_name VARCHAR(255),
        description TEXT,
        result TEXT,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE certifications (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT,
        certification_name VARCHAR(255),
        institution VARCHAR(255),
        date_obtained DATE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

     ```

5. You can finally use the site via 127.0.0.1, enjoy.

## Usage

1. Navigate to the homepage.
2. Register or log in using your credentials.
3. Once logged in, you can:
- Create or update your CV.
- Add education, work experience, skills, and projects.
- View the list of all user CVs.

## Technologies

- **PHP**: Backend language for server-side logic.
- **SQL**: Relational database for storing CV data.
- **Tailwind CSS**: Utility-first CSS framework for modern, responsive design.
- **HTML**: Markup language for web pages.

## Database Structure

The database `cv_db` contains the following tables:

### `users`
| Column              | Type         | Description                       |
|---------------------|--------------|-----------------------------------|
| `id`                | INT          | Primary key                       |
| `username`          | VARCHAR(100) | User's username                   |
| `password`          | VARCHAR(255) | User's password                   |
| `first_name`        | VARCHAR(100) | User's first name                 |
| `last_name`         | VARCHAR(100) | User's last name                  |
| `email`             | VARCHAR(255) | User's email                      |
| `phone`             | VARCHAR(20)  | User's phone number               |
| `linkedin`          | VARCHAR(255) | LinkedIn profile URL              |
| `github`            | VARCHAR(255) | GitHub profile URL                |
| `job_title`         | VARCHAR(255) | Current job title                 |
| `profile_description`| TEXT         | Profile description               |

### `education`
| Column              | Type         | Description                       |
|---------------------|--------------|-----------------------------------|
| `id`                | INT          | Primary key                       |
| `user_id`           | INT          | Foreign key to `users`            |
| `degree`            | VARCHAR(255) | Degree or qualification           |
| `institution`       | VARCHAR(255) | Name of the institution           |
| `start_date`        | DATE         | Education start date              |
| `end_date`          | DATE         | Education end date                |
| `description`       | TEXT         | Description of the education      |

### `experience`
| Column              | Type         | Description                       |
|---------------------|--------------|-----------------------------------|
| `id`                | INT          | Primary key                       |
| `user_id`           | INT          | Foreign key to `users`            |
| `company_name`      | VARCHAR(255) | Name of the company               |
| `job_title`         | VARCHAR(255) | Job title                         |
| `start_date`        | DATE         | Start date of the job             |
| `end_date`          | DATE         | End date of the job               |
| `description`       | TEXT         | Job description                   |

### `skills`
| Column              | Type         | Description                       |
|---------------------|--------------|-----------------------------------|
| `id`                | INT          | Primary key                       |
| `user_id`           | INT          | Foreign key to `users`            |
| `skill_name`        | VARCHAR(255) | Skill name                        |
| `skill_type`        | VARCHAR(255) | Type of skill (e.g., technical)   |

### `projects`
| Column              | Type         | Description                       |
|---------------------|--------------|-----------------------------------|
| `id`                | INT          | Primary key                       |
| `user_id`           | INT          | Foreign key to `users`            |
| `project_name`      | VARCHAR(255) | Name of the project               |
| `description`       | TEXT         | Description of the project        |
