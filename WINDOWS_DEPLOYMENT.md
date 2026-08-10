# Windows Server Deployment Guide

This guide covers deploying the MACE Spot Admission Portal on a **fresh Windows machine** (Windows 10/11 or Windows Server 2019/2022) using Docker Desktop.

Just like the Linux setup, you do **not** need to install PHP, Apache, or MySQL manually. Docker handles everything.

---

## Quick Start (For a Fresh Windows Machine)

### Step 1: Install Docker Desktop
1. Download Docker Desktop from: https://www.docker.com/products/docker-desktop/
2. Run the installer and follow the prompts.
3. When asked, enable **"Use WSL 2 instead of Hyper-V"** (recommended for Windows 10/11).
4. Restart your computer when prompted.
5. After restart, Docker Desktop will launch automatically. Wait for it to show **"Docker is running"** in the system tray.

### Step 2: Clone the Project
Open **PowerShell** and run:
```powershell
git clone git@github.com:JeremiaXavier/mace-admission-portal.git C:\admission_mace
cd C:\admission_mace
git checkout windows-server
```
> If you don't have Git installed, download it from https://git-scm.com/download/win

### Step 3: Configure Your Environment
Open `env.production` in Notepad and update:
- `app.baseURL` — Set to your server's IP or domain (e.g., `http://192.168.1.50/`)
- `database.default.password` — Set your chosen database password

Also open `docker-compose.windows.yml` and update the matching passwords:
- `MYSQL_PASSWORD` — Must match the password above
- `MYSQL_ROOT_PASSWORD` — Set a strong root password
- `PMA_PASSWORD` — Must match `MYSQL_ROOT_PASSWORD`

### Step 4: Build and Start Everything
In PowerShell, run:
```powershell
docker compose -f docker-compose.windows.yml up -d --build
```

That's it! Docker will:
1. Download and configure PHP 8.2 + Apache automatically
2. Download and configure MySQL 8.0 automatically
3. Download and configure phpMyAdmin automatically
4. Wait for MySQL to be fully ready
5. Automatically run database migrations
6. Start the web server

Open your browser and go to: **http://localhost**

---

## Accessing Your Application

| Service | URL |
|---|---|
| Admission Portal | http://localhost |
| Admin Dashboard | http://localhost/admin/login |
| phpMyAdmin | http://localhost:8080 |

---

## Useful Commands (PowerShell)

```powershell
# View live logs
docker compose -f docker-compose.windows.yml logs -f app

# Stop all containers
docker compose -f docker-compose.windows.yml down

# Restart everything
docker compose -f docker-compose.windows.yml restart

# Rebuild and update after pulling new code
docker compose -f docker-compose.windows.yml up -d --build
```

---

## Firewall & Network Access

To allow other computers on the same network to access the portal:

1. Open **Windows Defender Firewall** → **Advanced Settings**.
2. Click **Inbound Rules** → **New Rule**.
3. Select **Port** → TCP → enter `80, 8080` → **Allow the connection**.
4. Give it a name like "MACE Admission Portal" and save.

Other devices can then access the portal using this machine's local IP address (e.g., `http://192.168.1.50`). You can find your IP by running `ipconfig` in PowerShell.

---

## Disable Sleep / Power Settings

Windows may put the computer to sleep during inactivity, taking the portal offline.

1. Open **Settings** → **System** → **Power & Sleep**.
2. Set both **"Screen"** and **"Sleep"** to **Never**.

---

## Database Backup

### Via phpMyAdmin (Easiest)
1. Go to `http://localhost:8080`
2. Login with `root` and your `MYSQL_ROOT_PASSWORD`
3. Click `mace_admission` → **Export** → **Export**

### Via PowerShell
```powershell
docker exec mace_admission_db mysqldump -u root -p'strong_root_password' mace_admission > C:\backups\mace_backup.sql
```
