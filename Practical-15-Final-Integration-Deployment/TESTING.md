# StudentHub: Comprehensive Quality Assurance & Testing Suite

## Overview
This document specifies the end-to-end test cases and security verification protocols applied across all 15 practicals of the **StudentHub** academic web portal.

---

## Master Test Matrix

| Test ID | Module | Description / Scenario | Input Data | Expected Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **TC-01** | Practical 1 | Sitemap Hierarchy | Click through page links in `sitemap.html` | All 10 portal page anchors resolve properly | **PASS** |
| **TC-02** | Practical 2 | Semantic HTML5 | W3C markup validator check on pages | Clean semantic tags (`<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`) | **PASS** |
| **TC-03** | Practical 3 | Responsive Grid | Resize browser viewport below 768px | CSS Grid collapses smoothly from 3 columns to 1 column | **PASS** |
| **TC-04** | Practical 4 | Theme Toggle | Click Dark/Light toggle button | CSS variables invert; selection persists in `localStorage` | **PASS** |
| **TC-05** | Practical 4 | Modal & Slider | Click modal trigger; click prev/next slider | Popup opens/closes; slider translates cards dynamically | **PASS** |
| **TC-06** | Practical 5 | Name Validation | Enter `"R"` or `"Rahul123"` | Inline error: "Name must contain letters only (min 3 chars)" | **PASS** |
| **TC-07** | Practical 5 | Mobile Validation | Enter `"12345"` or `"5555555555"` | Inline error: Must be 10 digits starting with 6-9 | **PASS** |
| **TC-08** | Practical 5 | Password Meter | Type `"pass"` then `"Pass@1234"` | Bar transitions from Red (Weak) to Green (Strong) | **PASS** |
| **TC-09** | Practical 5 | Canvas CAPTCHA | Enter incorrect CAPTCHA code | Form submission cancelled; error displayed | **PASS** |
| **TC-10** | Practical 6 | Asynchronous Fetch | Click between Events, Students, FAQs tabs | Fetches respective JSON and updates cards without refresh | **PASS** |
| **TC-11** | Practical 6 | Live Search & Filter | Type `"Hackathon"` in search box | Immediately filters grid to matching cards | **PASS** |
| **TC-12** | Practical 7 | CSV & JSON Storage | Submit registration form via POST | Appends new row to `students.csv` & object to `students.json` | **PASS** |
| **TC-13** | Practical 8 | Database Schema | Run `test_db.php` | Connects to `studenthub`; reports active record counts | **PASS** |
| **TC-14** | Practical 9 | Duplicate Email | Register account with `admin@charusat.edu.in` | Displays error: "An account with email already exists" | **PASS** |
| **TC-15** | Practical 9 | Password Hashing | Inspect `users` table after registering | Password stored as 60-character Bcrypt hash (`$2y$10$...`) | **PASS** |
| **TC-16** | Practical 10 | Valid Login | Enter `admin@charusat.edu.in` / `Admin@123` | Session initialized; redirected to `admin_dashboard.php` | **PASS** |
| **TC-17** | Practical 10 | Invalid Password | Enter valid email with wrong password | Generic error shown; does not disclose password details | **PASS** |
| **TC-18** | Practical 10 | Unauthorized Access | Attempt to open `admin_dashboard.php` as student | Intercepted; redirected to `login.php?error=unauthorized` | **PASS** |
| **TC-19** | Practical 11 | Student CRUD | Create, Edit, and Delete student record | Database modified; UI reflects changes; JS confirms deletion | **PASS** |
| **TC-20** | Practical 11 | CSV Export | Click "Export CSV" button | Triggers download of `students_export_YYYY-MM-DD.csv` | **PASS** |
| **TC-21** | Practical 12 | Image Upload Cap | Attempt to upload 5MB image file | Upload blocked; error: "File size exceeds 2MB limit" | **PASS** |
| **TC-22** | Practical 12 | Allowed MIME Filter | Attempt to upload `.exe` or `.php` file | Upload rejected; error: "Invalid file type. Allowed: JPG, PNG" | **PASS** |
| **TC-23** | Practical 12 | Image Deletion | Delete event with custom poster | MySQL record deleted AND file unlinked from `uploads/` | **PASS** |
| **TC-24** | Practical 13 | REST API CRUD | Trigger POST, PUT, DELETE via Fetch API | Performs actions asynchronously returning HTTP 200/201 | **PASS** |
| **TC-25** | Practical 14 | Self-Deactivation | Admin clicks Deactivate on own account | Operation blocked; alert: "Cannot deactivate own account" | **PASS** |

---

## Security Verification Checklist

### 1. SQL Injection (SQLi) Defense
- **Payload Tested:** `' OR '1'='1` in login email, search box, and registration fields.
- **Result:** Defended. All queries use parameterized Prepared Statements (`mysqli_prepare` / `bind_param`). Payload is treated purely as string literals.

### 2. Cross-Site Scripting (XSS) Defense
- **Payload Tested:** `<script>alert('XSS')</script>` in Name, Remarks, and Description fields.
- **Result:** Defended. All outputs rendered into HTML tables and profile pages pass through `htmlspecialchars()`, neutralizing `<` and `>` into `&lt;` and `&gt;`.

### 3. Session Fixation Defense
- **Verification:** Before authentication, inspect session cookie. Upon successful password verification in `process_login.php`, verify that `session_regenerate_id(true)` generates a new session ID and deletes the prior session artifact.
- **Result:** Defended.

### 4. Malicious File Upload Defense
- **Payload Tested:** Uploading a script named `backdoor.php.jpg` or `.php`.
- **Result:** Defended. Strict whitelist checks the actual extension, and file names are cryptographically randomized using `uniqid('event_')`, preventing direct execution.
