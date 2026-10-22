<?php
// Practical 10: Admin Dashboard
session_start();

// Access Control Check: Must be logged in AND have 'admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php?error=unauthorized");
    exit();
}

require_once 'db.php';

// Fetch quick stats
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))['c'] ?? 0;
$totalEvents   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM events"))['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar">
    <div class="brand">StudentHub &bull; Administrator Portal</div>
    <div class="user-info">
      Signed in as: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
      <span class="role-badge role-admin">Admin</span>
      <a href="logout.php" class="btn-logout">Sign Out</a>
    </div>
  </nav>

  <div class="dashboard-container">
    <div class="welcome-card">
      <h2>Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
      <p>You have full administrative privileges to manage student records, campus events, and system settings.</p>
      <p style="font-size: 13px; color: #666;">
        Session Started: <?php echo date("Y-m-d H:i:s", $_SESSION['login_time']); ?> | 
        Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?>
      </p>
    </div>

    <!-- Quick Navigation to Subsequent Practicals -->
    <div class="action-cards">
      
      <div class="action-card">
        <h3>Manage Students</h3>
        <p>Total Enrolled: <strong><?php echo $totalStudents; ?></strong></p>
        <p>Perform CRUD operations, search by department, and export CSV data.</p>
        <a href="../Practical-11-Student-CRUD/index.php">Open Student CRUD &rarr;</a>
      </div>

      <div class="action-card">
        <h3>Manage Events</h3>
        <p>Active Events: <strong><?php echo $totalEvents; ?></strong></p>
        <p>Add workshops, upload event posters, edit schedules, and view registrations.</p>
        <a href="../Practical-12-Event-CRUD-Upload/index.php">Open Event CRUD &rarr;</a>
      </div>

      <div class="action-card">
        <h3>System Overview</h3>
        <p>RBAC Controls</p>
        <p>View administrative metrics, toggle account states, and role navigation.</p>
        <a href="../Practical-14-Admin-Dashboard-RBAC/dashboard.php">Open Master Admin &rarr;</a>
      </div>

    </div>
  </div>

</body>
</html>
