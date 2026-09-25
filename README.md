# SRM University Student Complaints & Grievance Redressal System

[![PHP](https://img.shields.io/badge/PHP-7.4%20%7C%208.x-777BB4.svg?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-5.7%20%7C%208.0-4479A1.svg?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3.svg?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Web portal for registering and redressing university department-level grievances from students and allowing administrative personnel to log, manage and resolve complaints with complete auditing.

---

## Table of Contents

- [Key Features](#-key-features)
- [Workflow & Architecture](#-workflow--architecture)
- [Project Structure](#-project-structure)
- [Getting Started](#-getting-started)
  - [Prerequisites](#prerequisites)
  - [Database Setup](#database-setup)
  - [Configuration & Running](#configuration--running)
- [Admin Features](#-admin-features)
- [Contributing](#-contributing)
- [License](#-license)

---

## Main Features

- **Grievance Filing**: Grievances can be filed using Name, Roll No., Email, Department, and Description.
- **Live Status Checking**: Immediate querying and filtering of Grievances based on their Status (`Pending`, `Resolved`).
- **Prepared Statements & Protection from SQL Injection**: Parameterized database queries.
- **Administrator Grievance Handling System**:
  - Search grievances using Student Name/ Register Number/ Department.
  - Change Status to `Resolved` instantly.
  - Safe deletion of Invalid/Duplicate entries.
- **Responsive Design & Notiflix Messages**: Responsive layouts with animations on notifications using Notiflix library.

---

## Workflow Diagram

```mermaid
flowchart LR
    A[Student] -->|Submits Complaint| B[submisgrievance.php]
    B -->|Saved As 'Pending'| C[(MySQL: application)]
    D[Admin] -->|Logs in| E[Admin Dashboard]
    E -->|Search/Filter| C
    E -->|Marks as solved| F[mark_grievance_solved.php]
    E -->|Deletes record| G[delete_final.php]
    F -->|Changes status to 'Resolved'| C
    G -->|Deletes record from database| C
```

---

## Project Structure

```text
SRM-Complaint-Register-Website/
├── Rutu1.php / Rutu1.css      # Home page and initial interface for the portal 
├── Rutu2.php / Rutu2.css      # Form for submitting grievances  
├── Rutu_login.php             # Administrator login page 
├── Rutu_register.php          # Registration form
├── Rutu_response.php          # Page to receive and confirm grievance submission
├── submit_grievance.php       # Backend grievance submission processor
├── mark_solved.php            # Resolution processor 
├── delete.php / delete_final.php  # Deletion of complaints 
├── search.php                 # Multicriteria grievance search
├── search_pending.php         # Search for pending grievances
├── search_resolved.php        # Search for grievances resolved history
├── bootstrap/                 # Bootstrap CSS/JS files 
├── notiflix/                  # Notification library for Notiflix
├── images/                    # Graphic files
├── LICENSE                    # MIT License
└── README.md                  # Documentation for the project
```
---

## Starting the Program

### Requirements

- **XAMPP / WAMP / LAMP** installation along with PHP 7.4+ and MySQL.
- Any recent web browser.

### Database Configuration

1. Start **phpMyAdmin** at (`http://localhost/phpmyadmin`).
2. Create a database `30mm`.
3. Create the table `application` using below SQL commands:
   ```sql
   CREATE DATABASE IF NOT EXISTS `30mm`;
   USE `30mm`;

   CREATE TABLE IF NOT EXISTS `application` (
       `id` INT AUTO_INCREMENT PRIMARY KEY,
       `name` VARCHAR(150) NOT NULL,
       `rollno` VARCHAR(50) NOT NULL,
       `email` VARCHAR(150) NOT NULL,
       `department` VARCHAR(100) NOT NULL,
       `grievances` TEXT NOT NULL,
       `status` ENUM('pending', 'resolved') DEFAULT 'pending',
       `submitted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
   ```
   
### Setup & Execution

1. Put the project folder in the web root directory (e.g., `htdocs/SRM-Complaint-Register-Website`).
2. Make sure the database connection details in `submit_grievance.php` correspond to your MySQL installation settings:
   ```php
   $cn30 = mysqli_connect("localhost", "root", "", "30mm");
   ```
3. Launch your browser and go to the URL:
   ```text
   http://localhost/SRM-Complaint-Register-Website/Rutu1.php
   ```

---

## License

This project is [MIT licensed](LICENSE).
