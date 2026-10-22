# Practical 15: Final System Integration, End-to-End Testing & Local Deployment

## Aim
To perform comprehensive end-to-end integration of all 15 modular practicals into the unified **StudentHub** academic portal, establish a centralized launch dashboard with environment diagnostics, verify cross-module functionality via automated and manual test matrices, and document production deployment steps.

---

## 3-Tier Architecture Overview

```
┌─────────────────────────────────────────────────────────────┐
│                   TIER 1: PRESENTATION                      │
│   • Semantic HTML5 Pages (Practical 2)                      │
│   • Responsive Grid & Flexbox (Practical 3)                 │
│   • DOM Manipulation & Interactive Widgets (Practical 4)    │
│   • Client-Side Form Validation & Canvas CAPTCHA (Prac 5)   │
│   • Single Page Application (SPA) Fetch UI (Practical 13)   │
└──────────────────────────────┬──────────────────────────────┘
                               │ HTTP / JSON API (Fetch)
┌──────────────────────────────▼──────────────────────────────┐
│                    TIER 2: APPLICATION                      │
│   • PHP Server-Side Form Processor & Sanitizer (Prac 7)     │
│   • Bcrypt Hashing & Duplicate Email Checks (Prac 9)        │
│   • Authentication & Session RBAC Middleware (Prac 10)      │
│   • Student Record CRUD Engine & CSV Stream (Prac 11)       │
│   • Multi-Part File Upload Controller (Prac 12)             │
│   • RESTful JSON API Endpoint (Prac 13)                     │
│   • Admin KPI Metrics & Status Toggler (Prac 14)            │
└──────────────────────────────┬──────────────────────────────┘
                               │ MySQLi / Prepared Statements
┌──────────────────────────────▼──────────────────────────────┐
│                       TIER 3: DATA                          │
│   • Flat Files (students.csv, students.json) (Prac 7)       │
│   • Normalized MySQL Relational Schema (Prac 8, 15)         │
│     - users, students, events, event_registrations          │
│   • Uploads Directory (uploads/) (Prac 12)                  │
└─────────────────────────────────────────────────────────────┘
```

---

## Deliverables in this Practical
1. `index.php`: Master Integration Hub with real-time PHP/MySQL diagnostics, active session badge, and 1-click launch cards for all 15 practicals.
2. `database/studenthub_full.sql`: Full self-contained schema with tables, foreign keys, indexes, and initial test accounts.
3. `TESTING.md`: 25-point quality assurance matrix covering all functional and security scenarios (SQLi, XSS, Session Fixation, File Upload).
4. `DEPLOYMENT.md`: Step-by-step local setup instructions for XAMPP on macOS, Windows, and Linux.
5. `db.php`: Database connection helper with status detection.
6. `css/style.css`: Clean, beginner stylesheet.

---

## Viva Questions & Answers

### Q1: What is System Integration Testing, and how does it differ from Unit Testing?
- **Unit Testing:** Tests individual components, functions, or scripts in isolation (e.g. testing whether `validateEmail()` regex correctly identifies an `@` symbol).
- **Integration Testing:** Tests how multiple distinct modules interact with each other as a whole system. For example, verifying that when a student registers in Practical 9:
  1. The user account is saved to MySQL,
  2. The password can be authenticated by Practical 10 (Login),
  3. The record appears immediately inside the Student CRUD table in Practical 11,
  4. The student can view events created in Practical 12,
  5. The registration increments the KPI counter in Practical 14.

### Q2: Explain the 3-Tier Web Architecture used throughout StudentHub.
1. **Presentation Tier (Client/Frontend):** Runs in the user's browser using HTML5, CSS3, and JavaScript. Responsible for presenting data, collecting user inputs, and providing immediate feedback.
2. **Application Tier (Server/Logic):** Runs on the web server using PHP. Responsible for business logic, input sanitization, access authorization, file handling, and API routing.
3. **Data Tier (Storage/Database):** Comprises the MySQL database and file storage systems. Responsible for ACID transaction compliance, data integrity, and fast relational querying.

### Q3: Why are Database Indexes (`INDEX`) added on columns like `email`, `role`, and `event_date`?
- Without an index, the database must perform a **Full Table Scan**, reading every single row from disk sequentially to find a match.
- An index creates a B-Tree data structure on the specified column. When a query searches for `WHERE email = ?` or filters by `WHERE event_date >= ?`, the database looks up the record in logarithmic time \(O(\log N)\) rather than linear time \(O(N)\), drastically speeding up search performance as tables grow large.

### Q4: In server deployment, why is `chmod 777` recommended only for specific folders rather than the whole project?
- `777` grants Read, Write, and Execute permissions to everyone (Owner, Group, and Public).
- Applying `777` to an entire codebase allows anyone on the server to alter application code.
- **Best Practice:** Keep application PHP scripts set to `644` (or `755` for directories). Only grant write permissions to folders that strictly require file writes by PHP (such as `uploads/` for event posters and `data/` for CSV/JSON logs).

### Q5: How do the 15 practicals follow a natural Software Development Life Cycle (SDLC)?
- **Practicals 1–3:** Requirements, Wireframes, Information Architecture, and UI/UX Layout.
- **Practicals 4–6:** Client-side scripting, DOM manipulation, Form Validation, and Asynchronous Data Loading.
- **Practicals 7–8:** Server-side foundations, File I/O, Database Modeling, and Schema Normalization.
- **Practicals 9–10:** Security, Password Hashing, Session Management, and Role-Based Authorization.
- **Practicals 11–12:** Core Business Operations (CRUD), Search Filters, Data Exports, and File Uploads.
- **Practical 13:** Modern Web Evolution (RESTful APIs and Single Page Applications).
- **Practicals 14–15:** Executive Administration, RBAC Auditing, Quality Assurance, and System Deployment.
