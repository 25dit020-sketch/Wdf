# StudentHub: Local Deployment & Server Configuration Guide

## Minimum System Requirements
- **Web Server:** Apache 2.4+ (or Nginx)
- **PHP Version:** PHP 7.4, 8.0, 8.1, or 8.2 with `mysqli`, `json`, `mbstring`, and `fileinfo` extensions enabled
- **Database Engine:** MySQL 5.7+ or MariaDB 10.4+
- **Browser:** Any modern standards-compliant browser (Chrome, Firefox, Safari, Edge)

---

## Step-by-Step Installation on XAMPP (macOS / Windows / Linux)

### Step 1: Copy Codebase to Web Server Root
1. Copy or symlink the `wdf` repository into your web server's public document directory:
   - **macOS (XAMPP):** `/Applications/XAMPP/xamppfiles/htdocs/wdf`
   - **Windows (XAMPP):** `C:\xampp\htdocs\wdf`
   - **Linux:** `/var/www/html/wdf`

### Step 2: Start Apache and MySQL Services
- Open the **XAMPP Control Panel**.
- Start both **Apache** and **MySQL** modules. Ensure the status indicators turn green.

### Step 3: Import the Master Database
1. Open your browser and navigate to **phpMyAdmin**:
   `http://localhost/phpmyadmin`
2. Click on the **Import** tab in the top navigation bar.
3. Click **Choose File** and select:
   `Practical-15-Final-Integration-Deployment/database/studenthub_full.sql`
4. Click **Go** at the bottom of the page.
5. Verify that the database `studenthub` and its four tables (`users`, `students`, `events`, `event_registrations`) are created.

*Alternative Terminal Import Command:*
```bash
# macOS / Linux
/Applications/XAMPP/xamppfiles/bin/mysql -u root -p < database/studenthub_full.sql

# Windows Command Prompt
C:\xampp\mysql\bin\mysql.exe -u root -p < database\studenthub_full.sql
```

### Step 4: Configure Filesystem Permissions
Ensure that PHP has write permissions for file uploads and flat-file logging:
```bash
# On macOS / Linux terminal:
chmod -R 777 Practical-07-PHP-Form-Processing/data/
chmod -R 777 Practical-12-Event-CRUD-Upload/uploads/
chmod -R 777 Practical-15-Final-Integration-Deployment/uploads/
```

### Step 5: Launch the Integrated Master Hub
Open your browser and visit:
```
http://localhost/wdf/Practical-15-Final-Integration-Deployment/index.php
```
You should see the green diagnostic banner confirming that PHP and MySQL are active, with all 15 practical links ready for navigation.

---

## Production Security Hardening Checklist
Before deploying this application to a live public server:
1. **Disable Display Errors:** Set `display_errors = Off` and `log_errors = On` in `php.ini` so internal database error messages are not exposed to public users.
2. **Enforce HTTPS (SSL/TLS):** Ensure all traffic is encrypted with Let's Encrypt or an SSL certificate.
3. **Database Credentials:** Update `DB_PASS` in `db.php` from blank (`''`) to a strong, isolated database password.
4. **Secure Session Cookies:** Configure `session.cookie_httponly = 1`, `session.cookie_secure = 1`, and `session.use_strict_mode = 1` in `php.ini`.
