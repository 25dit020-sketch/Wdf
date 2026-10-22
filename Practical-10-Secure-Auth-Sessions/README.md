# Practical 10: Secure User Authentication, Session Management, and Role-Based Access Control (RBAC)

## Aim
To implement a secure authentication workflow in PHP and MySQL with password verification (`password_verify`), session fixation protection (`session_regenerate_id`), role-based access control (RBAC) routing users to dedicated Admin or Student Dashboards, and secure session destruction upon logout.

---

## Authentication & Authorization Flow

```
[Login Form: login.php]
         │ (HTTP POST)
         ▼
[Backend Processor: process_login.php]
  ├── 1. Fetch user by email via Prepared Statement
  ├── 2. Verify password: password_verify($password, $user['password'])
  ├── 3. Check account status ($user['status'] === 'active')
  ├── 4. Regenerate Session ID: session_regenerate_id(true)
  ├── 5. Set $_SESSION['user_id'], $_SESSION['user_role'], etc.
  └── 6. Evaluate Role:
         ├── If 'admin'   --> Redirect to admin_dashboard.php
         └── If 'student' --> Redirect to student_dashboard.php
```

---

## File Summary
- `login.php`: Login form with error messages, logout notices, and pre-configured demo test accounts.
- `process_login.php`: Validates credentials, executes Bcrypt verification, regenerates session tokens, and applies role routing.
- `admin_dashboard.php`: Protected administrative hub (requires `admin` role).
- `student_dashboard.php`: Protected student portal displaying enrolled courses and registered events.
- `logout.php`: Cleanses session variables, deletes client session cookies, and destroys server session files.
- `db.php`: Database connection helper.
- `css/style.css`: Clean, beginner stylesheet.

---

## Demo Test Credentials
| Role | Email Address | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin@charusat.edu.in` | `Admin@123` |
| **Student** | `aarav.it@charusat.edu.in` | `Student@123` |

---

## Viva Questions & Answers

### Q1: How do PHP Sessions work under the hood?
- When `session_start()` is called:
  1. PHP generates a unique, cryptographically random string called the **Session ID** (e.g. `PHPSESSID=4e7bc8...`).
  2. This ID is transmitted to the client's browser as a cookie (`PHPSESSID`).
  3. A corresponding temporary storage file is created on the server (typically in `/tmp` or `xampp/tmp`).
  4. On subsequent requests, the browser sends the `PHPSESSID` cookie back to the server in HTTP headers. PHP locates the corresponding session file and automatically populates the `$_SESSION` superglobal array.

### Q2: What is Session Fixation, and how does `session_regenerate_id(true)` protect against it?
- **Session Fixation:** An attack where an adversary tricks a victim into authenticating using a known session ID (e.g., via a link like `http://example.com/login.php?PHPSESSID=attacker_fixed_id`). Once the victim logs in, the attacker uses the same session ID to hijack their authenticated session.
- **Protection:** Calling `session_regenerate_id(true)` immediately after successful password verification discards the old session ID, generates a brand new session identifier, and deletes the old session data file on the server. The attacker's pre-set ID is rendered useless.

### Q3: What is the difference between `session_unset()` and `session_destroy()`?
- `session_unset()` (or `$_SESSION = array();`): Clears all variables currently stored in the `$_SESSION` array in memory for the current script execution, but the session file on the server and the cookie remain intact.
- `session_destroy()`: Deletes the physical session data file stored on the server's disk.
- **Best Practice for Logout:** Unset the array, clear the browser cookie via `setcookie()`, and then call `session_destroy()`.

### Q4: What is Role-Based Access Control (RBAC)? Why is hiding links in the UI not enough?
- **RBAC:** A security paradigm where system privileges and page access are restricted based on assigned roles (e.g. Admin, Faculty, Student).
- **Why UI hiding is insufficient:** Simply hiding the "Admin Panel" link from student users does not stop a student from manually typing `admin_dashboard.php` into the browser URL bar. Every protected script must enforce an explicit server-side access check:
  ```php
  if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
      header("Location: login.php?error=unauthorized");
      exit();
  }
  ```

### Q5: How does `password_verify()` work without ever decrypting the password?
- Passwords hashed with Bcrypt cannot be decrypted because hashing is a **one-way function**.
- When a user enters their plaintext password at login, `password_verify($password, $storedHash)` extracts the algorithm, cost factor, and random salt from `$storedHash`, re-hashes the incoming plaintext password using those identical parameters, and compares the two hashes in constant time to avoid timing attacks. If they match, the password is correct.
