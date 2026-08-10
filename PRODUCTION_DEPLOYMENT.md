# Production Deployment Guide

This guide outlines how to deploy the MACE Spot Admission Portal onto a fresh, newly installed Linux server (e.g., Ubuntu Desktop 24.04 or Ubuntu Server). 

Because the application is fully Dockerized, **you do not need to install PHP, Apache, or MySQL on your server**. Docker handles all of these dependencies internally in isolated containers.

---

## Quick Start (For a Fresh Ubuntu OS)
If you are on a completely fresh OS, here are the only 4 commands you need to get the entire portal live:

**1. Install Docker & Docker Compose**
```bash
sudo apt-get update
sudo apt-get install -y docker.io docker-compose-v2
```

**2. Copy/Clone the project**
```bash
git clone <your-repository-url> /home/user/admission_mace
cd /home/user/admission_mace
```

**3. Build and Start Everything**
*(You may want to edit `docker-compose.yml` first to set secure database passwords)*
```bash
sudo docker compose up -d --build
```

That's it! The container will automatically wait for the database to boot and run the migrations for you. You can now open a browser on that machine and go to `http://localhost`.

---

## Detailed Guide

### 1. Advanced Docker Installation (Optional)

First, log into your fresh server via SSH. Run the following commands to install Docker and Docker Compose.

```bash
# Update package list
sudo apt-get update

# Install prerequisites
sudo apt-get install -y ca-certificates curl gnupg

# Add Docker's official GPG key
sudo install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg
sudo chmod a+r /etc/apt/keyrings/docker.gpg

# Set up the Docker repository
echo \
  "deb [arch="$(dpkg --print-architecture)" signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
  "$(. /etc/os-release && echo "$VERSION_CODENAME")" stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker Engine and Docker Compose
sudo apt-get update
sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

## 2. Transfer or Clone the Project

Bring your project code to the production server. If you are using Git:

```bash
git clone <your-repository-url> /opt/admission_mace
cd /opt/admission_mace
```

## 3. Configure Production Secrets

Before spinning up the containers, open the `docker-compose.yml` file and update the default passwords for security:

```bash
nano docker-compose.yml
```

**What to change in `docker-compose.yml`:**
1. Under the `app` service:
   - Change `app.baseURL=https://admission.macesoft.in/` to match your actual live domain.
   - Change `database.default.password=mace_password` to a strong, secure password.
2. Under the `db` service:
   - Change `MYSQL_PASSWORD: mace_password` to match the password above.
   - Change `MYSQL_ROOT_PASSWORD: strong_root_password` to a highly secure root password.
3. Under the `phpmyadmin` service:
   - Change `PMA_PASSWORD: strong_root_password` to match the MySQL Root Password above.

Save and exit (`Ctrl+O`, `Enter`, `Ctrl+X`).

## 4. Start the Application

Once your passwords are secure, tell Docker to build the image and start the stack in the background:

```bash
sudo docker compose up -d --build
```

Docker will now:
1. Download PHP, Apache, MySQL, and phpMyAdmin.
2. Install all required PHP extensions (Intl, GD, Zip, MySQLi).
3. Install CodeIgniter's Composer dependencies optimally.
4. Set up the file permissions for the persistent `writable/` folder.
5. Wire the web app, database, and phpMyAdmin together securely.
6. **Automatically run database migrations** to create your tables on first boot.

## 5. Accessing Your Application

Everything is now live!

- **Main Application**: Accessible on port 80.
  - URL: `http://<your-server-ip>/` (or your domain name if DNS is pointed).
  
- **Admin Dashboard**:
  - URL: `http://<your-server-ip>/admin/login`
  
- **phpMyAdmin (Database Visualizer)**: Accessible on port 8080.
  - URL: `http://<your-server-ip>:8080/`
  - Login Username: `root`
  - Login Password: `<your_MYSQL_ROOT_PASSWORD>`

## 7. Useful Docker Commands for Maintenance

- **View live application logs:**
  ```bash
  sudo docker compose logs -f app
  ```
- **Restart the application:**
  ```bash
  sudo docker compose restart
  ```
- **Stop everything safely:**
  ```bash
  sudo docker compose down
  ```
- **Update the app after pulling new code from git:**
  ```bash
  sudo docker compose up -d --build
  ```

---

## 8. Special Considerations for Ubuntu Desktop 24.04

Since you are running Ubuntu Desktop instead of Ubuntu Server, keep the following in mind to ensure your application remains available:

1. **Disable Sleep/Suspend:** Desktop operating systems are often configured to go to sleep after inactivity. 
   - Go to **Settings > Power** and set "Blank Screen" and "Automatic Suspend" to **Never** so the server doesn't shut down while applicants are trying to register.
2. **Firewall (UFW):** If you plan to access this portal from other devices on the network, ensure your firewall allows web traffic.
   ```bash
   sudo ufw allow 80/tcp
   sudo ufw allow 8080/tcp
   ```
3. **Port Conflicts:** Ensure you don't already have a local version of Apache or Nginx running on your desktop. If you do, it will block Docker from binding to port 80. You can stop local apache with `sudo systemctl stop apache2 && sudo systemctl disable apache2`.
4. **Local Access:** You can access the portal directly on the desktop machine by opening a browser and going to `http://localhost`. To access it from other devices, use the desktop's local IP address (e.g., `http://192.168.1.50`), which you can find by running `ip a`.

---

## 9. Backing Up Your Database

Since your application stores critical applicant data, you should regularly back up your database. You have two easy ways to do this:

### Method A: Via phpMyAdmin (Easiest)
1. Open your browser and go to `http://<your-server-ip>:8080/`.
2. Log in using the username `root` and your `MYSQL_ROOT_PASSWORD`.
3. Click on the `mace_admission` database on the left sidebar.
4. Click the **Export** tab at the top.
5. Leave the format as **SQL** and click **Export**. This will download a complete backup file to your computer.

### Method B: Via Command Line
If you want to quickly generate a backup file directly on the server, run this command:
```bash
sudo docker exec mace_admission_db mysqldump -u root -p'strong_root_password' mace_admission > /home/user/mace_admission_backup_$(date +%F).sql
```
*(Make sure to replace `'strong_root_password'` with the actual root password you set in `docker-compose.yml`)*
