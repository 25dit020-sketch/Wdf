# Practical 9: User Registration with Password Hashing & Duplicate Validation

## Aim
To implement a secure student registration module for the **StudentHub** portal using PHP and MySQL, incorporating server-side sanitization, duplicate email and roll number checks via prepared statements, industry-standard password hashing using Bcrypt (`password_hash`), and transactional multi-table insertion (`users` and `students`).

---

## Registration Workflow

```
[Student Registration Form: register.php]
                 │ (HTTP POST)
                 ▼
[Backend Processor: process_register.php]
                 │
  ├── 1. Sanitize & Validate (Required fields, email format, pass length >= 8)
  ├── 2. Duplicate Check: SELECT id FROM users WHERE email = ?
  ├── 3. Duplicate Check: SELECT id FROM students WHERE roll_no = ?
  ├── 4. Password Hash: password_hash($password, PASSWORD_DEFAULT)
  ├── 5. Transaction Start (mysqli_begin_transaction)
  │       ├── INSERT INTO users (name, email, password, role)
  │       ├── $newUserId = mysqli_insert_id($conn)
  │       └── INSERT INTO students (user_id, roll_no, department, ...)
  └── 6. Commit Transaction & Flash Success Banner
                 │
                 ▼
[Redirect to register.php with Success Notice]
```

---

## File Summary
- `register.php`: Registration form with sticky inputs (preserves user typing on error), validation feedback banners, and login link.
- `process_register.php`: Transactional registration handler with duplicate email/roll checks, Bcrypt hashing, and dual-table insertion.
- `db.php`: Database connection script.
- `css/style.css`: Clean, beginner stylesheet.

---

## Viva Questions & Answers

### Q1: Why must passwords NEVER be stored in plain text in a database?
- If an attacker gains unauthorized access to the database (through SQL injection, exposed database backups, or insider threats), all user accounts, credentials, and associated personal information are instantly compromised.
- Because many users reuse passwords across multiple websites (banking, social media, email), exposing plain-text passwords puts users at severe risk across the entire internet.

### Q2: Why are `md5()` and `sha1()` obsolete for password hashing? What makes `password_hash()` (Bcrypt) superior?
- **Flaws of MD5 / SHA-1:**
  - They are general-purpose cryptographic checksum algorithms designed to be **extremely fast**.
  - Modern GPUs can calculate billions of MD5 hashes per second, making them trivial to crack using precomputed Rainbow Tables or brute force attacks.
- **Superiority of `password_hash()` (Bcrypt):**
  - **Adaptive Work Factor (Cost):** Bcrypt is deliberately computationally intensive (slow), throttling brute force attempts.
  - **Automatic Unique Salt:** Generates a cryptographically secure random salt for every single password automatically. Two users with the exact same password (`Password123`) will have completely different stored hashes, defeating Rainbow Tables.

### Q3: What is a Salt in password hashing?
- A **Salt** is a sequence of random bytes concatenated with the user's password before the hashing algorithm executes.
- It guarantees that identical passwords produce distinct hash strings. In PHP, `password_hash()` includes the algorithm identifier, cost factor, and random salt directly within the resulting 60-character string (e.g. `$2y$10$...`).

### Q4: Why is a database Transaction (`mysqli_begin_transaction`) essential when inserting into both `users` and `students`?
- Registration touches two related tables: `users` (credentials) and `students` (academic profile).
- Without a transaction, if the first `INSERT INTO users` succeeds, but the second `INSERT INTO students` fails (e.g. invalid department or database connection drop), you end up with a **corrupted database state**: an orphan user account with no student profile that can never register again or properly log in.
- Transactions guarantee the **Atomicity** property of ACID: either **both** insertions succeed, or both are completely undone via `mysqli_rollback()`.

### Q5: How do Prepared Statements prevent SQL Injection during registration?
- Prepared statements pre-compile the SQL template on the database server.
- The input values (`$email`, `$fullName`, `$hashedPassword`) are sent as parameters separated from the query instruction.
- The SQL engine treats user input strictly as literal string values, preventing malicious payloads like `' OR '1'='1` from altering the query structure.
