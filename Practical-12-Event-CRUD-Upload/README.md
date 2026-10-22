# Practical 12: Campus Event Management with File Upload (Event Posters) & CRUD

## Aim
To build an Event Management system for **StudentHub** featuring full CRUD operations and secure multi-part file uploads for event poster banners, with MIME-type verification, size capping, sanitized unique filename generation, and physical file cleanup on deletion.

---

## File Upload Flowchart & Security Layers

```
[HTML Form: enctype="multipart/form-data"]
                  │ (HTTP POST)
                  ▼
[Temporary Upload Directory: xampp/tmp/phpXXXXXX]
                  │
  ├── 1. Error Check: $_FILES['poster']['error'] === UPLOAD_ERR_OK
  ├── 2. Extension Check: in_array(ext, ['jpg', 'jpeg', 'png', 'webp'])
  ├── 3. File Size Cap: $_FILES['poster']['size'] <= 2MB
  ├── 4. Unique Filename: 'event_' . uniqid() . '.' . ext
  └── 5. Move: move_uploaded_file(tmp_path, uploads/dest_path)
                  │
                  ▼
[Database Record: INSERT INTO events (poster_image = 'event_xxx.jpg')]
```

---

## File Summary
- `index.php`: Responsive CSS Grid showcasing upcoming events with poster graphics, category badges, dates, seat capacities, and Edit/Delete controls.
- `add_event.php`: Event publishing form supporting multi-part file uploads and file verification.
- `edit_event.php`: Edit event details with live poster thumbnail and optional image replacement (cleans up old image).
- `delete_event.php`: Deletes database row and unlinks associated poster image from `uploads/`.
- `uploads/`: Secure local directory storing uploaded poster files.
- `db.php`: Database connection helper.
- `css/style.css`: Clean, beginner stylesheet.

---

## Viva Questions & Answers

### Q1: Why is `enctype="multipart/form-data"` mandatory for file uploads?
- By default, forms encode data using `application/x-www-form-urlencoded`, which converts characters into ASCII key-value pairs (e.g. `name=Rahul&roll=23IT001`). This format is incapable of transmitting binary data such as images, audio, or PDFs.
- `multipart/form-data` splits the HTTP request body into separate delimited sections, allowing each file to be sent in its raw binary stream alongside standard text inputs.

### Q2: What are the keys of the `$_FILES['poster']` array in PHP?
1. `$_FILES['poster']['name']`: Original file name from the user's computer (e.g. `my_banner.png`).
2. `$_FILES['poster']['type']`: MIME type provided by the browser (e.g. `image/png`).
3. `$_FILES['poster']['tmp_name']`: Temporary file path where PHP stored the uploaded file on the server (e.g. `/tmp/php7aB2`).
4. `$_FILES['poster']['error']`: Error code integer (`UPLOAD_ERR_OK = 0`, `UPLOAD_ERR_INI_SIZE = 1`, `UPLOAD_ERR_NO_FILE = 4`).
5. `$_FILES['poster']['size']`: File size in bytes.

### Q3: Why must `move_uploaded_file()` be used instead of standard `copy()` or `rename()`?
- `move_uploaded_file()` performs an internal security verification: it checks that the source file is a genuine file uploaded via an HTTP POST request.
- Using plain `copy()` or `rename()` creates a critical vulnerability where an attacker could manipulate the filename parameter to trick the server into copying or moving sensitive local system files (e.g. `/etc/passwd`).

### Q4: What security hazards arise from unrestricted file uploads, and how are they prevented?
- **Risks:**
  - An attacker could upload a PHP shell script (`shell.php`) masked as an image and execute arbitrary server commands.
  - An attacker could upload oversized files to exhaust server disk storage.
  - Uploading a file named `../../index.php` (Path Traversal) could overwrite core application code.
- **Five Layers of Defense Implemented:**
  1. Strict whitelist validation of allowed extensions (`['jpg', 'jpeg', 'png', 'webp']`).
  2. File size capping at 2MB (`$fileSize <= 2 * 1024 * 1024`).
  3. Renaming files with randomized unique IDs (`uniqid()`) so attackers cannot guess or invoke script names.
  4. Storing files in an isolated `uploads/` folder.
  5. Fallback placeholder graphics (`default_event.svg`) if no file is submitted.

### Q5: Why is `unlink()` used in `delete_event.php` and `edit_event.php`?
- `unlink($filePath)` deletes the physical file from the server's hard drive.
- When an event is deleted from MySQL or replaced with a new banner, failing to call `unlink()` leaves the old file stranded on the filesystem ("orphan file"). Over time, thousands of orphan images waste disk space.
