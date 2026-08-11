# Internship Tracker System — Web Application

A full-featured web-based Internship Tracker System built with PHP and MySQL, converted from a Python/Tkinter desktop application. The system provides a centralized platform for students and administrators to manage internship activities, track progress, post job opportunities, and handle support tickets efficiently through a modern web interface.

---

## Features

### Student Panel
- **Student Registration & Login** — Secure account creation and authentication
- **Browse Job Postings** — View available internship opportunities posted by admin
- **Apply for Internships** — Submit applications directly through the portal
- **Track Application Status** — Monitor application progress in real time
- **Resume Upload** — Upload and manage resume/CV documents
- **Support Tickets** — Raise and track support queries with admin

### Admin Panel
- **Admin Dashboard** — Overview of all students, applications, and activity
- **Post Job Opportunities** — Create and manage internship listings
- **Manage Applications** — View, approve, or reject student applications
- **Application Status Management** — Update and track status for each applicant
- **Resume Viewer** — Access and review student-uploaded resumes
- **Support Ticket Management** — Respond to and resolve student support tickets
- **Student Record Management** — View and manage all registered students

---

## Technologies Used

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap
- **Backend**: PHP
- **Database**: MySQL (phpMyAdmin)
- **Server**: Apache (XAMPP for local development)

---

## Project Objective

To digitize and streamline internship management by providing a web-based platform that replaces manual tracking methods, enabling efficient communication between students and administrators, real-time status updates, and organized record keeping of all internship activities.

---

## My Role

**Full Stack Developer** — Designed and developed the complete web application including frontend UI, backend PHP logic, database integration, authentication system, file upload functionality, and deployment on live hosting.

---

## How to Run Locally

1. Clone the repository:
   ```bash
   git clone https://github.com/NirbhayKudale
   ```

2. Move to XAMPP htdocs:
   ```
   D:/xampp/htdocs/internshiptracker/
   ```

3. Start **Apache** and **MySQL** in XAMPP Control Panel

4. Import the database:
   - Open `http://localhost/phpmyadmin`
   - Create database named `internship_db`
   - Import the `.sql` file from `/database/` folder

5. Configure database connection:
   - Open `db.php`
   - Update credentials if needed:
   ```php
   $conn = new mysqli("localhost", "root", "", "internship_db");
   ```

6. Open in browser:
   ```
   http://localhost/internshiptracker/
   ```

---


## Database Tables

| Table | Description |
|---|---|
| `users` | Student registration and login data |
| `jobs` | Internship job postings by admin |
| `applications` | Student job applications and status |
| `resumes` | Uploaded resume file paths |
| `support_tickets` | Student support queries and responses |

---

## Screenshots

> Screenshots coming soon.

---


## Author

**Nirbhay Kudale**
Python Full Stack with Data Analyst Developer | BBA (Computer Applications)
Maharashtra, India

## Author

**Nirbhay Kudale**
Full Stack Developer | BBA (Computer Applications)
Maharashtra, India

[![GitHub](https://img.shields.io/badge/GitHub-NirbhayKudale-black?logo=github)](https://github.com/NirbhayKudale)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Nirbhay%20Kudale-blue?logo=linkedin)](https://www.linkedin.com/in/nirbhay-kudale-87ba21428)
