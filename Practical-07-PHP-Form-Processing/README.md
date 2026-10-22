# Practical 7: Server-Side Form Handling and File-Based Data Storage with PHP

## Aim
To implement server-side student registration form processing using PHP, sanitize and validate user input, and persist records across dual flat-file storage formats (**CSV** and **JSON**), with a dedicated viewer script to display records in an HTML table.

---

## Architecture & Workflow

```
[Browser Form: index.php]
         │ (HTTP POST)
         ▼
[Server: process.php]
         ├── 1. Sanitize & Validate (filter_var, trim, preg_match)
         ├── 2. Append to data/students.csv (fputcsv)
         ├── 3. Append to data/students.json (json_decode / json_encode)
         └── 4. Redirect to view.php?status=success
         ▼
[Browser: view.php] (Displays all records in an HTML table with CSV download)
```

---

## File Summary
- `index.php`: Student registration form with required fields, dropdowns, and status feedback banners.
- `process.php`: Server-side processing engine that sanitizes inputs and writes to both flat files.
- `view.php`: Displays saved student records in an HTML table with total count and CSV download link.
- `data/students.csv`: Comma-separated values log of registrations.
- `data/students.json`: Formatted JSON array of student objects.
- `css/style.css`: Clean, beginner stylesheet.

---

## How to Run
1. Start the Apache server in XAMPP.
2. Ensure this folder is accessible via your web server (e.g. symlinked or copied into `/Applications/XAMPP/xamppfiles/htdocs/wdf/Practical-07-PHP-Form-Processing/` or run PHP's built-in server):
   ```bash
   cd Practical-07-PHP-Form-Processing
   php -S localhost:8000
   ```
3. Open `http://localhost:8000/index.php` in your browser.

---

## Viva Questions & Answers

### Q1: What is the difference between `$_GET` and `$_POST` in PHP?
- **`$_GET`:**
  - Appends submitted form data directly to the URL query string (`page.php?name=Rahul&roll=23IT001`).
  - Data is visible in browser history, bookmarks, and server access logs.
  - Limited data length (~2048 characters).
  - Used for **idempotent requests** like search, filters, and fetching data.
- **`$_POST`:**
  - Sends data inside the HTTP request body.
  - Data is not visible in the URL bar.
  - Supports large payloads and file uploads (`multipart/form-data`).
  - Used for **state-changing operations** (inserting, updating, passwords, sensitive data).

### Q2: Why is `htmlspecialchars()` essential when displaying user submitted data?
- `htmlspecialchars()` converts special HTML characters into their corresponding HTML entities (e.g. `<` becomes `&lt;`, `>` becomes `&gt;`, `"` becomes `&quot;`).
- Without it, if a user submits `<script>alert('Hacked')</script>`, the browser would execute that script when viewing the table. This vulnerability is called **Cross-Site Scripting (XSS)**. `htmlspecialchars()` neutralizes malicious script tags into harmless plain text.

### Q3: Explain how `fputcsv()` works in PHP.
- `fputcsv($fileHandle, $fieldsArray)` formats an array of strings into a standard comma-separated line (CSV format), escaping special characters or quotation marks as necessary, and writes it directly to an open file pointer.
- It automatically handles commas inside user inputs by wrapping strings in quotes (e.g. `"Web Development, AI"`), preventing corruption of CSV columns.

### Q4: Compare Flat-File Storage (CSV/JSON) with Relational Databases (MySQL).
| Feature | Flat Files (CSV / JSON) | Relational Database (MySQL) |
| :--- | :--- | :--- |
| **Setup** | Zero setup; requires only file system read/write permissions | Requires a database server, user credentials, and table schemas |
| **Portability** | High; easy to download, inspect, or transfer | Requires database backup/dump (`mysqldump`) |
| **Concurrency** | Weak; multiple concurrent writes can lead to file corruption | High; handles thousands of simultaneous transactions with ACID properties |
| **Querying** | Manual looping in PHP; slow for large data | Powerful SQL queries with indexing, JOINs, and aggregation |
| **Scalability** | Good only for small logs / configuration files | Enterprise scale |

### Q5: What is a Race Condition in file handling, and how can it be avoided?
- A race condition occurs when two users submit a form at the exact same millisecond. Both PHP processes open `students.json`, read the existing data, and overwrite each other's changes, leading to lost updates.
- In PHP, this can be mitigated using file locks:
  ```php
  $fp = fopen("data/students.json", "w");
  if (flock($fp, LOCK_EX)) { // Exclusive lock
      fwrite($fp, $data);
      flock($fp, LOCK_UN); // Release lock
  }
  fclose($fp);
  ```
  For large multi-user apps, switching to MySQL (Practical 8) is the standard industry solution.
