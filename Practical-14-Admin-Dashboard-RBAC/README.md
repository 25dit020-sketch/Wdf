# Practical 14: Administrative Dashboard, Dynamic Role-Based Navigation & Account Management

## Aim
To design and implement a Master Administrative Dashboard for **StudentHub** featuring executive KPI metrics, dynamic role-based navigation, and a user account access management system with real-time status toggling (active/deactive) and self-lockout prevention.

---

## Administrative Architecture

```
[Master Admin Dashboard: dashboard.php]
  │
  ├── 1. Access Control: $_SESSION['user_role'] === 'admin'
  ├── 2. KPI Metrics:
  │       ├── Total Enrolled Students (COUNT(*) FROM students)
  │       ├── Total Campus Events (COUNT(*) FROM events)
  │       ├── Total Registered Users (COUNT(*) FROM users)
  │       └── Total Event RSVPs (COUNT(*) FROM event_registrations)
  ├── 3. Dynamic Navbar (Overview, Students CRUD, Events CRUD, REST API)
  └── 4. User Status Management:
          └── Action Button -> toggle_status.php?id=X&action=(activate|deactivate)
                 │
                 ├── Self-Deactivation Check (id != $_SESSION['user_id'])
                 ├── UPDATE users SET status = ? WHERE id = ?
                 └── Redirect to dashboard.php
```

---

## File Summary
- `dashboard.php`: Administrative control center displaying statistical cards, role navigation, and full user accounts table.
- `toggle_status.php`: State controller script toggling user access between `active` and `inactive` states with authorization safeguards.
- `db.php`: Database connection helper.
- `css/style.css`: Clean, beginner stylesheet.

---

## Viva Questions & Answers

### Q1: How does Dynamic Role-Based Navigation work in PHP?
- PHP inspects the role stored in the active session (`$_SESSION['user_role']`).
- When generating HTML navigation links, conditional `if/else` checks render administrative links (e.g. User Management, System Diagnostics, CRUD Modules) exclusively to administrators, while hiding them from student users.

### Q2: Why is "Soft Deactivation" (`status = 'inactive'`) preferred over "Hard Deletion" (`DELETE FROM users`)?
1. **Audit Trail & Historical Integrity:** Hard deletion removes records completely, breaking event RSVP histories, grade logs, or payment records linked via foreign keys.
2. **Reversibility:** A deactivated account can be restored instantly with one click if a student re-enrolls or resolves an issue.
3. **Security Investigations:** Preserves forensic access logs, email records, and IP timestamps for investigation if an account is flagged for abuse.

### Q3: How do SQL aggregate functions like `COUNT(*)` optimize dashboard performance?
- Calculating totals using `COUNT(*)` executes directly in the database engine, returning a single integer row over the network.
- This avoids pulling thousands of complete student rows into PHP memory just to call `count($rows)`, reducing network I/O, server RAM utilization, and execution latency.

### Q4: Why is the Self-Deactivation Prevention check essential?
- If the only administrator inadvertently clicks "Deactivate" on their own user account, their session would become locked out upon subsequent requests. Without another active admin in the database, the entire administrative panel would become inaccessible without direct manual intervention into the MySQL console or phpMyAdmin.
- Enforcing `$userId == $_SESSION['user_id']` prevents this administrative deadlock.

### Q5: How do PHP Sessions persist across different practical folders on localhost?
- By default, PHP session cookies (`PHPSESSID`) are set for the root domain (`path=/`).
- Any PHP script running under `http://localhost/...` that invokes `session_start()` has access to the exact same session storage file on the server. This allows Practical 10 (Login) to set session variables that are immediately recognized across Practical 11, 12, 14, and 15.
