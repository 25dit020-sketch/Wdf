<?php
// Practical 14: Master Admin Dashboard & RBAC Management
session_start();

// Strict Access Control: Admin role required
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../Practical-10-Secure-Auth-Sessions/login.php?error=unauthorized");
    exit();
}

require_once 'db.php';

// 1. Fetch Executive Metric Counts
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))['c'] ?? 0;
$totalEvents   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM events"))['c'] ?? 0;
$totalUsers    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'] ?? 0;
$totalRsvps    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM event_registrations"))['c'] ?? 0;

// 2. Fetch User Accounts for Management
$usersQuery = "SELECT id, name, email, role, status, created_at FROM users ORDER BY id ASC";
$usersResult = mysqli_query($conn, $usersQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 14 - Admin Dashboard &amp; User Management | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Dynamic Role-Based Navbar -->
  <nav class="navbar">
    <div class="brand">StudentHub &bull; Master Administration</div>
    <ul class="nav-menu">
      <li><a href="dashboard.php" class="active">Overview</a></li>
      <li><a href="../Practical-11-Student-CRUD/index.php">Students</a></li>
      <li><a href="../Practical-12-Event-CRUD-Upload/index.php">Events</a></li>
      <li><a href="../Practical-13-PHP-JSON-API-Fetch/index.html">REST API (SPA)</a></li>
    </ul>
    <div>
      <span style="font-size: 13px; margin-right: 10px;">
        Signed in: <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
      </span>
      <a href="../Practical-10-Secure-Auth-Sessions/logout.php" class="btn-logout">Logout</a>
    </div>
  </nav>

  <div class="container">
    
    <!-- Feedback Alerts -->
    <?php if (isset($_GET['msg'])): ?>
      <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['err'])): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['err']); ?></div>
    <?php endif; ?>

    <!-- Executive Metric Cards -->
    <div class="stats-grid">
      <div class="stat-card students">
        <h3><?php echo $totalStudents; ?></h3>
        <p>Enrolled Students</p>
      </div>

      <div class="stat-card events">
        <h3><?php echo $totalEvents; ?></h3>
        <p>Campus Events</p>
      </div>

      <div class="stat-card users">
        <h3><?php echo $totalUsers; ?></h3>
        <p>Total User Accounts</p>
      </div>

      <div class="stat-card rsvps">
        <h3><?php echo $totalRsvps; ?></h3>
        <p>Event Registrations</p>
      </div>
    </div>

    <!-- User Account Status Management -->
    <div class="section-card">
      <h3>System User Accounts &amp; Access Control</h3>
      <p style="font-size: 13px; color: #666;">
        Manage user authorization and activate/deactivate portal access in real-time. Deactivated users cannot log into the system.
      </p>

      <table>
        <thead>
          <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Account Status</th>
            <th>Registered Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($u = mysqli_fetch_assoc($usersResult)): ?>
            <tr>
              <td>#<?php echo $u['id']; ?></td>
              <td><strong><?php echo htmlspecialchars($u['name']); ?></strong></td>
              <td><?php echo htmlspecialchars($u['email']); ?></td>
              <td>
                <span style="text-transform: capitalize; font-weight:bold; color: <?php echo ($u['role'] === 'admin') ? '#856404' : '#004085'; ?>;">
                  <?php echo $u['role']; ?>
                </span>
              </td>
              <td>
                <span class="status-badge <?php echo ($u['status'] === 'active') ? 'status-active' : 'status-inactive'; ?>">
                  <?php echo strtoupper($u['status']); ?>
                </span>
              </td>
              <td style="font-size: 12px; color: #666;"><?php echo $u['created_at']; ?></td>
              <td>
                <?php if ($u['id'] == $_SESSION['user_id']): ?>
                  <span style="font-size: 11px; color: #999;">(Current Session)</span>
                <?php else: ?>
                  <?php if ($u['status'] === 'active'): ?>
                    <a href="toggle_status.php?id=<?php echo $u['id']; ?>&action=deactivate" 
                       onclick="return confirm('Deactivate access for <?php echo htmlspecialchars(addslashes($u['name'])); ?>?');" 
                       class="btn-toggle btn-deactivate">Deactivate</a>
                  <?php else: ?>
                    <a href="toggle_status.php?id=<?php echo $u['id']; ?>&action=activate" 
                       class="btn-toggle btn-activate">Activate</a>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>

</body>
</html>
