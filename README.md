# TP-CV-PHP

## Description

This project is a **CV Management System** built with **PHP** and **Tailwind CSS**. Users can create, update, and view their CVs, including personal information, work experience, education, skills, and projects. Additionally, an admin or other users can view all CVs in a list.

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
   git clone https://github.com/username/cv-management-system.git

2. Navigate into the project directory:
    ```bash
    cd cv-management-system

3. Install dependencies: If your project requires any dependencies (for example, a specific PHP version or other libraries), list them here.

4. Set up the database:
   - Import the provided `cv_db.sql` file into your MySQL database:
     ```bash
     mysql -u username -p database_name < cv_db.sql
     ```
   - Make sure your `db.php` file contains the correct database credentials:
     ```php
     <?php
     $host = 'localhost';
     $db   = 'cv_db';
     $user = 'your_username';
     $pass = 'your_password';
     $charset = 'utf8mb4';

     $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
     $options = [
         PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
         PDO::ATTR_EMULATE_PREPARES   => false,
     ];

     try {
         $pdo = new PDO($dsn, $user, $pass, $options);
     } catch (PDOException $e) {
         throw new PDOException($e->getMessage(), (int)$e->getCode());
     }
     ?>
     ```

5. Run the project on a local server. You can use tools like **XAMPP** or **WAMP** for local development:
   - Place the project in the `htdocs` folder (for XAMPP):
     ```
     /xampp/htdocs/cv-management-system
     ```
   - Start Apache and MySQL, then navigate to `http://localhost/cv-management-system`.

## Usage

1. Navigate to the homepage.
2. Register or log in using your credentials.
3. Once logged in, you can:
- Create or update your CV.
- Add education, work experience, skills, and projects.
- View the list of all user CVs.

## Technologies

- **PHP**: Backend language for server-side logic.
- **MySQL**: Relational database for storing CV data.
- **Tailwind CSS**: Utility-first CSS framework for modern, responsive design.
- **HTML**: Markup language for web pages.

## Database Structure

The database `cv_db` contains the following tables:

### `users`
| Column              | Type         | Description                       |
|---------------------|--------------|-----------------------------------|
| `id`                | INT          | Primary key                       |
| `first_name`        | VARCHAR(255) | User's first name                 |
| `last_name`         | VARCHAR(255) | User's last name                  |
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

### `work_experience`
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
