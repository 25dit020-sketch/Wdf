# Practical 11: Student Record Management with Full CRUD Operations & CSV Export

## Aim
To implement a complete Student Record Management module performing full **CRUD** (Create, Read, Update, Delete) operations using PHP and MySQL prepared statements, along with live keyword search, department filtering, and on-demand **CSV data export**.

---

## CRUD Architecture & Operations

| Operation | Action | Script | Primary SQL Statement |
| :--- | :--- | :--- | :--- |
| **Create** | Add New Student | `add_student.php` | `INSERT INTO users (...)` & `INSERT INTO students (...)` |
| **Read** | View & Filter Records | `index.php` | `SELECT s.*, u.name, u.email FROM students s JOIN users u ...` |
| **Update** | Edit Details | `edit_student.php` | `UPDATE users ...` & `UPDATE students ...` |
| **Delete** | Remove Record | `delete_student.php`| `DELETE FROM users WHERE id = ?` *(Cascades)* |
| **Export** | Download Spreadsheet | `export_csv.php` | Streams query results directly to `php://output` |

---

## File Summary
- `index.php`: Master table view with real-time keyword search, department dropdown filter, student count badge, and actions.
- `add_student.php`: Form to register new student records with automatic credential generation and duplicate checks.
- `edit_student.php`: Pre-populated edit interface updating both credentials and academic records.
- `delete_student.php`: Deletion script invoked with JavaScript confirmation dialog (`confirm()`).
- `export_csv.php`: Streams matching records as a `.csv` file attachment.
- `db.php`: Database connection helper.
- `css/style.css`: Clean, beginner stylesheet.

---

## Viva Questions & Answers

### Q1: What does CRUD stand for and how does each operation map to SQL?
- **C - Create:** Adds new rows to the database using `INSERT INTO table_name (...) VALUES (...)`.
- **R - Read:** Retrieves existing records using `SELECT columns FROM table_name WHERE condition`.
- **U - Update:** Modifies existing data fields using `UPDATE table_name SET column = value WHERE condition`.
- **D - Delete:** Removes existing rows using `DELETE FROM table_name WHERE condition`.

### Q2: Why is a `WHERE` clause critical in `UPDATE` and `DELETE` queries?
- If an `UPDATE` or `DELETE` query is executed without an explicit `WHERE id = ?` clause, MySQL applies the modification or deletion to **every single row** in the entire table, resulting in irreversible data loss.

### Q3: How does the HTTP `Content-Disposition` header trigger browser file downloads?
- By default, browsers attempt to render any received content (HTML, images, text) directly in the viewport.
- Sending the header:
  ```php
  header('Content-Disposition: attachment; filename="students.csv"');
  ```
  instructs the browser that the response is a binary or attachment download, prompting the user with a "Save As" dialog rather than rendering text on screen.

### Q4: Why is it risky to trigger deletions via HTTP GET links (`delete.php?id=X`) in production?
1. **Web Crawlers & Pre-fetching:** Search engine bots or browser pre-fetch accelerators that follow `<a>` links will automatically delete records without user intention.
2. **Cross-Site Request Forgery (CSRF):** An attacker can embed an image tag `<img src="http://example.com/delete.php?id=5">` on a third-party site to silently delete records.
- **Production Solution:** Trigger deletions using HTTP `POST` methods protected by CSRF tokens and confirmation dialogs.

### Q5: What is `php://output` and why is it preferred for large CSV exports?
- `php://output` is a write-only stream that allows PHP to write directly to the output buffer/network stream in real time.
- Instead of building a massive string in PHP's RAM (which can trigger `Fatal Error: Allowed memory size exhausted`), rows are streamed sequentially via `fputcsv($output, $row)` directly to the user's browser, maintaining minimal memory overhead.
