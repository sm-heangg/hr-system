<p align="center">
    <a href="#" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

<p align="center">
    <img src="https://img.shields.io/github/stars/sm-heangg/hr-system?style=social" alt="Stars">
    <img src="https://img.shields.io/github/forks/sm-heangg/hr-system?style=social" alt="Forks">
    <img src="https://img.shields.io/github/license/sm-heangg/hr-system" alt="License">
    <img src="https://img.shields.io/badge/Laravel-12-red" alt="Laravel v12">
</p>

# 📌 HR System (Open Source)

A modern and production-ready **Human Resource Management System** built using **Laravel 12**, featuring real-time interactions, permission control, QR attendance, and a full Filament admin panel.

This project is designed for **learning**, **teaching**, and **real HR operations** such as employees, departments, attendance, leave requests, and more.

---

# 🧰 **Technology Stack**

### Backend & Framework
- **Laravel v12** (PHP framework)
- **MySQL** (Relational database)
- **SimpleSoftwareIO / Simple-Qr** (QR Code generator)

### Frontend
- **Tailwind CSS** (Utility-first styling)
- **Livewire** (Realtime response without page reload)

### Admin Panel & Security
- **Filament v4** (Free admin dashboard)
- **Filament Shield** (Role & Permission for Filament)

### DevOps & Tools
- **Git** (Version control)
- **GitHub** (Repository hosting)
- **Ngrok** (HTTPS public URL for testing / mobile QR scanner)

---

# 📥 **Installation Guide (Full Setup)**

Follow the steps below to install and run the project locally.

---

## ✔ 1. Clone the repository

```bash
git clone https://github.com/sm-heangg/hr-system.git
cd hr-system

✔ 2. Install PHP dependencies (Laravel)
composer install

✔ 3. Install frontend dependencies (Tailwind + Livewire)
npm install
npm run build   # or npm run dev

✔ 4. Create .env file
cp .env.example .env

✔ 5. Configure your database in .env
DB_DATABASE=hr_system
DB_USERNAME=root
DB_PASSWORD=

✔ 6. Generate application key
php artisan key:generate

✔ 7. Run migrations
php artisan migrate

✔ 8. Run migrations

php artisan shield:install
php artisan shield:generate

✔ 9. Create Filament admin user
php artisan make:filament-user

✔ 10. Start the development server
php artisan serve

✔ 11. Start the development server
composer require simplesoftwareio/simple-qrcode



