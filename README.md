# 🎓 MACE Spot Admission Portal

![MACE Logo](https://macesoft.in/assets/admin/img/logo.png)

A modern, robust, and Dockerized web application built with CodeIgniter 4 to handle B.Tech Spot Admissions at **Mar Athanasius College of Engineering**.

---

## ✨ Features

- **Dynamic Student Registration**: Secure multi-step wizard for students to submit their KEAM details, current admission status, and option preferences.
- **Real-Time Validations**: Instant uniqueness checks on Mobile Numbers, Email Addresses, and KEAM Ranks via AJAX.
- **Admin Dashboard**: Comprehensive dashboard for viewing and managing applicants across branches and categories.
- **Advanced Exporting**: 1-click exporting to structured CSVs and beautifully formatted PDFs (using DOMPDF) complete with metadata and signature blocks.
- **Interactive Allotment**: Admins can officially "Admit" students to specific branches, and seamlessly "Undo" admissions if a student changes their mind.
- **Total State Control**: 1-click toggle to open or close the entire portal to new registrations.
- **Dockerized**: 100% contained environment ready for immediate, frictionless deployment on any Linux machine.

---

## 🛠 Tech Stack

- **Backend:** PHP 8.2 & CodeIgniter 4
- **Frontend:** Vanilla JS & Tailwind CSS (Custom compiled tokens)
- **Database:** MySQL 8.0
- **Containerization:** Docker & Docker Compose
- **PDF Generation:** DOMPDF

---

## 🚀 Quick Start (Production)

Deploying the portal is incredibly simple. All dependencies (PHP, Apache, MySQL) are handled internally by Docker.

1. **Install Docker** on your server.
2. **Clone this repository:**
   ```bash
   git clone git@github.com:JeremiaXavier/mace-admission-portal.git
   cd mace-admission-portal
   ```
3. **Secure your passwords** in `docker-compose.yml`.
4. **Boot the stack:**
   ```bash
   sudo docker compose up -d --build
   ```
5. **Run Database Migrations:**
   ```bash
   sudo docker exec -it mace_admission_app php spark migrate
   ```

*(For detailed instructions, see the included `PRODUCTION_DEPLOYMENT.md`)*

---

## 🔒 Security & Persistence

- **Session & Upload Persistence:** The `/writable` directory is mapped securely to a Docker volume, meaning your application configuration (`settings.json`) and session states survive container restarts.
- **Hidden Credentials:** Database configuration uses secure internal Docker networking. The web application connects safely to the MySQL container without exposing database ports to the outside world.

---

> Designed & Built for Mar Athanasius College of Engineering.
