<?php
// Practical 11: Student Records Management (Read / List / Search)
session_start();
require_once 'db.php';

// Search and Filter parameters
$search = trim($_GET['search'] ?? '');
$deptFilter = trim($_GET['department'] ?? '');

// Build Dynamic SQL Query
$sql = "SELECT s.id, u.name, u.email, s.roll_no, s.department, s.semester, s.cgpa, s.contact_no 
        FROM students s 
        JOIN users u ON s.user_id = u.id 
        WHERE 1=1";

$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (u.name LIKE ? OR s.roll_no LIKE ? OR u.email LIKE ?)";
    $searchTerm = "%" . $search . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

if (!empty($deptFilter)) {
    $sql .= " AND s.department = ?";
    $params[] = $deptFilter;
    $types .= "s";
}

$sql .= " ORDER BY s.id DESC";

$stmt = mysqli_prepare($conn, $sql);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$totalRows = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 11 - Student Management CRUD | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    
    <div class="top-bar">
      <div>
        <h2>Student Management System</h2>
        <span style="font-size: 13px; color: #666;">Total Registered Students: <strong><?php echo $totalRows; ?></strong></span>
      </div>
      <div>
        <a href="add_student.php" class="btn btn-primary">+ Add New Student</a>
        <a href="export_csv.php?search=<?php echo urlencode($search); ?>&department=<?php echo urlencode($deptFilter); ?>" class="btn btn-success">&#128190; Export CSV</a>
      </div>
    </div>

    <!-- Feedback Message Alert -->
    <?php if (isset($_GET['msg'])): ?>
      <div class="alert alert-success">
        <?php echo htmlspecialchars($_GET['msg']); ?>
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['err'])): ?>
      <div class="alert alert-danger">
        <?php echo htmlspecialchars($_GET['err']); ?>
      </div>
    <?php endif; ?>

    <!-- Search & Filter Form -->
    <form method="GET" action="index.php" class="search-filter-box">
      <input type="text" name="search" placeholder="Search by name, roll no, email..." value="<?php echo htmlspecialchars($search); ?>">
      
      <select name="department">
        <option value="">All Departments</option>
        <option value="Information Technology" <?php echo ($deptFilter === 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
        <option value="Computer Engineering" <?php echo ($deptFilter === 'Computer Engineering') ? 'selected' : ''; ?>>Computer Engineering</option>
        <option value="Computer Science" <?php echo ($deptFilter === 'Computer Science') ? 'selected' : ''; ?>>Computer Science &amp; Engg</option>
        <option value="Electronics & Comm" <?php echo ($deptFilter === 'Electronics & Comm') ? 'selected' : ''; ?>>Electronics &amp; Comm</option>
        <option value="Mechanical Engineering" <?php echo ($deptFilter === 'Mechanical Engineering') ? 'selected' : ''; ?>>Mechanical Engineering</option>
      </select>

      <button type="submit" class="btn btn-primary">Filter</button>
      <?php if (!empty($search) || !empty($deptFilter)): ?>
        <a href="index.php" class="btn" style="background:#6c757d; color:white;">Clear</a>
      <?php endif; ?>
    </form>

    <!-- Students Data Table -->
    <?php if ($totalRows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Roll No</th>
            <th>Full Name</th>
            <th>Email Address</th>
            <th>Department</th>
            <th>Sem</th>
            <th>CGPA</th>
            <th>Mobile</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><code><?php echo htmlspecialchars($row['roll_no']); ?></code></td>
              <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><span class="badge"><?php echo htmlspecialchars($row['department']); ?></span></td>
              <td><?php echo $row['semester']; ?></td>
              <td><?php echo number_format($row['cgpa'], 2); ?></td>
              <td><?php echo htmlspecialchars($row['contact_no']); ?></td>
              <td>
                <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                <a href="delete_student.php?id=<?php echo $row['id']; ?>" 
                   onclick="return confirm('Are you sure you want to permanently delete student <?php echo htmlspecialchars(addslashes($row['name'])); ?>?');" 
                   class="btn btn-delete">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="text-align: center; color: #777; padding: 25px;">No student records found matching the filter.</p>
    <?php endif; ?>

    <div style="margin-top: 20px; font-size: 13px;">
      <a href="../Practical-10-Secure-Auth-Sessions/admin_dashboard.php">&larr; Return to Admin Dashboard</a>
    </div>

  </div>

</body>
</html>
