<p align="center">
    <a href="#" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <img src="https://img.shields.io/github/stars/sm-heangg/hr-system?style=social" alt="Stars">
    <img src="https://img.shields.io/github/forks/sm-heangg/hr-system?style=social" alt="Forks">
    <img src="https://img.shields.io/github/license/sm-heangg/hr-system" alt="License">
    <img src="https://img.shields.io/badge/Laravel-Framework-red" alt="Laravel">
</p>

# 📌 HR System

A simple and customizable **Human Resource Management System** built using **Laravel** and **MySQL**.  
This system is designed for learning, practice, and real-world HR workflow simulation — including employees, departments, attendance, leave requests, and more.

---

## 🚀 Features

- Employee management (add, edit, delete staff)
- Department & Position management
- Attendance tracking
- Leave request & approval workflow
- CRUD operations using Laravel MVC
- Authentication (Login/Logout)
- Clean & understandable Laravel project structure

---

## 🏗️ Tech Stack

- **Backend:** Laravel (PHP)
- **Database:** MySQL
- **Frontend:** Blade / Bootstrap (default Laravel UI)
- **Tools:** Composer, Artisan CLI, Laravel Migrations

---

## 📥 Installation

Follow these steps to run the project locally:

```bash
# 1. Clone the repository
git clone https://github.com/sm-heangg/hr-system.git
cd hr-system

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Configure your database in .env
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

# 6. Run migrations
php artisan migrate

# 7. Start the server
php artisan serve
