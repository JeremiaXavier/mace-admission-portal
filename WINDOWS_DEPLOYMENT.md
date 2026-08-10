# Windows Server Deployment Guide (Existing/Live Server)

This guide is for deploying the MACE Spot Admission Portal on a **Windows Server that is already in use**
(Windows Server 2019/2022 or Windows 10/11).

We use **Docker Desktop** instead of XAMPP. Unlike XAMPP:
- Docker is fully isolated and will not interfere with anything else running on the server.
- It runs a production-grade Apache + PHP 8.2 + MySQL 8.0 stack.
- It can comfortably handle 200+ simultaneous users.
- You do **not** need to install PHP, Apache, or MySQL manually on Windows.

---

## Step 1: Check What's Using Port 80

Open **PowerShell as Administrator** and run:
```powershell
netstat -ano | findstr ":80"
```

- If the result is **empty** → Port 80 is free. Docker can use it directly.
- If you see results → something (likely IIS) is already using port 80.
  - In that case, we will run the admission portal on **port 8000** instead (instructions below).

---

## Step 2: Install Docker Desktop

1. Download **Docker Desktop for Windows** from: https://www.docker.com/products/docker-desktop/
2. Run the installer. When asked, choose **"Use WSL 2 instead of Hyper-V"** (recommended).
3. Restart the computer when prompted.
4. After restart, Docker Desktop will appear in the system tray. Wait until it shows **"Docker is running"**.

> If you are on **Windows Server 2019/2022** (not desktop), install **Docker Engine** instead:
> https://docs.docker.com/engine/install/

---

## Step 3: Clone the Repository

Open **PowerShell as Administrator** and run:
```powershell
git clone git@github.com:JeremiaXavier/mace-admission-portal.git C:\admission_mace
cd C:\admission_mace
git checkout windows-server
```
> If Git is not installed: https://git-scm.com/download/win

---

## Step 4: Configure Environment

Open `env.production` in Notepad and set:
```
app.baseURL = 'http://<your-server-IP>/'
database.default.password = your_strong_db_password
```

Open `docker-compose.windows.yml` and update all three password fields:
```yaml
MYSQL_PASSWORD: your_strong_db_password       # must match above
MYSQL_ROOT_PASSWORD: your_strong_root_password
PMA_PASSWORD: your_strong_root_password       # must match root password
```

---

## Step 5: Handle Port Conflicts

### If Port 80 is FREE:
Use the default `docker-compose.windows.yml` as-is. No changes needed.

### If Port 80 is TAKEN (e.g. IIS is running):
Open `docker-compose.windows.yml` and change the `app` ports line from:
```yaml
ports:
  - "80:80"
```
to:
```yaml
ports:
  - "8000:80"
```
The admission portal will then be accessible at **http://localhost:8000** instead of port 80.

> **Optional:** If you want it to remain accessible on port 80, you can set up **IIS as a Reverse Proxy**
> to forward requests from port 80 to the Docker container on port 8000.
> Ask your IT team or see: https://docs.microsoft.com/en-us/iis/extensions/url-rewrite-module/reverse-proxy-with-url-rewrite-v2-and-application-request-routing

---

## Step 6: Start the Application

In PowerShell, run:
```powershell
docker compose -f docker-compose.windows.yml up -d --build
```

Docker will automatically:
1. ✅ Download and configure PHP 8.2 + Apache
2. ✅ Download and configure MySQL 8.0 (production-grade, NOT XAMPP)
3. ✅ Download and configure phpMyAdmin
4. ✅ Wait for MySQL to be fully ready before booting
5. ✅ Run all database migrations automatically
6. ✅ Start the web server

---

## Step 7: Access the Application

| Service | URL (Port 80) | URL (Port 8000 if conflict) |
|---|---|---|
| Admission Portal | http://localhost | http://localhost:8000 |
| Admin Dashboard | http://localhost/admin/login | http://localhost:8000/admin/login |
| phpMyAdmin | http://localhost:8080 | http://localhost:8080 |

To access from other computers on the network, replace `localhost` with the server's local IP (run `ipconfig` to find it).

---

## Step 8: Open Firewall Ports

To allow other devices to access the portal:

1. Open **Windows Defender Firewall** → **Advanced Settings** → **Inbound Rules** → **New Rule**
2. Choose **Port** → TCP
3. Enter the ports used: `80, 8000, 8080` (whichever applies)
4. Select **Allow the connection** → Apply to all profiles
5. Name the rule **"MACE Admission Portal"** and save.

---

## Step 9: Prevent Windows Sleep

If Windows goes to sleep, Docker shuts down and the portal goes offline.

- Open **Settings** → **System** → **Power & Sleep**
- Set both **Screen** and **Sleep** to **Never**

---

## Useful Management Commands (PowerShell)

```powershell
# View live application logs
docker compose -f docker-compose.windows.yml logs -f app

# Stop all containers (does NOT delete data)
docker compose -f docker-compose.windows.yml down

# Restart everything
docker compose -f docker-compose.windows.yml restart

# Pull latest code from GitHub and redeploy
git pull
docker compose -f docker-compose.windows.yml up -d --build
```

---

## Database Backup

### Via phpMyAdmin (Easiest — Recommended)
1. Open browser → `http://localhost:8080`
2. Login: Username `root`, Password = your `MYSQL_ROOT_PASSWORD`
3. Click `mace_admission` → **Export** tab → **Export**
4. This downloads a complete `.sql` backup file.

### Via PowerShell
```powershell
docker exec mace_admission_db mysqldump -u root -p'your_strong_root_password' mace_admission > C:\Backups\mace_backup_%date:~-4,4%%date:~-10,2%%date:~-7,2%.sql
```

---

## Why Docker instead of XAMPP?

| Feature | XAMPP | Docker (This Setup) |
|---|---|---|
| Apache Version | Old bundled version | Latest stable |
| PHP Version | Varies | PHP 8.2 (exact requirement) |
| MySQL Version | Old bundled version | MySQL 8.0 official |
| Isolation | Shares with OS | Fully isolated |
| Concurrent Users | Poor (not tuned) | Production-ready |
| Conflict Risk | High (uses same ports as IIS) | Low (configurable ports) |
| Update Process | Manual reinstall | Single command |
