<?php
// Practical 10: Student Dashboard
session_start();

// Access Control Check: Must be logged in AND have 'student' role
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'student') {
    header("Location: login.php?error=unauthorized");
    exit();
}

require_once 'db.php';

// Fetch current student profile details
$userId = $_SESSION['user_id'];
$profileStmt = mysqli_prepare($conn, "SELECT s.*, u.name, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE u.id = ? LIMIT 1");
mysqli_stmt_bind_param($profileStmt, "i", $userId);
mysqli_stmt_execute($profileStmt);
$studentProfile = mysqli_fetch_assoc(mysqli_stmt_get_result($profileStmt));
mysqli_stmt_close($profileStmt);

// Fetch registered events for this student
$eventsQuery = "SELECT e.title, e.category, e.event_date, e.venue, er.status 
                FROM event_registrations er 
                JOIN events e ON er.event_id = e.id 
                JOIN students s ON er.student_id = s.id 
                WHERE s.user_id = $userId";
$myEvents = mysqli_query($conn, $eventsQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 9px 12px;
      text-align: left;
      font-size: 14px;
    }
    th {
      background-color: #003366;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-top: 15px;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <!-- Top Navbar -->
  <nav class="navbar">
    <div class="brand">StudentHub &bull; Student Portal</div>
    <div class="user-info">
      Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>
      <span class="role-badge role-student">Student</span>
      <a href="logout.php" class="btn-logout">Sign Out</a>
    </div>
  </nav>

  <div class="dashboard-container">
    
    <!-- Profile Card -->
    <div class="welcome-card">
      <h2>Student Academic Profile</h2>
      <div class="profile-grid">
        <div><strong>Full Name:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></div>
        <div><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></div>
        <div><strong>Roll No:</strong> <?php echo htmlspecialchars($studentProfile['roll_no'] ?? 'N/A'); ?></div>
        <div><strong>Department:</strong> <?php echo htmlspecialchars($studentProfile['department'] ?? 'N/A'); ?></div>
        <div><strong>Semester:</strong> <?php echo htmlspecialchars($studentProfile['semester'] ?? 'N/A'); ?></div>
        <div><strong>Contact No:</strong> <?php echo htmlspecialchars($studentProfile['contact_no'] ?? 'N/A'); ?></div>
      </div>
    </div>

    <!-- Registered Events Card -->
    <div class="welcome-card">
      <h3>My Event Registrations</h3>
      <?php if ($myEvents && mysqli_num_rows($myEvents) > 0): ?>
        <table>
          <thead>
            <tr>
              <th>Event Title</th>
              <th>Category</th>
              <th>Date</th>
              <th>Venue</th>
              <th>RSVP Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($event = mysqli_fetch_assoc($myEvents)): ?>
              <tr>
                <td><strong><?php echo htmlspecialchars($event['title']); ?></strong></td>
                <td><?php echo htmlspecialchars($event['category']); ?></td>
                <td><?php echo htmlspecialchars($event['event_date']); ?></td>
                <td><?php echo htmlspecialchars($event['venue']); ?></td>
                <td><span style="color:green; font-weight:bold;"><?php echo strtoupper($event['status']); ?></span></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p style="color: #666;">You have not registered for any events yet. Check out upcoming campus events!</p>
      <?php endif; ?>
    </div>

  </div>

</body>
</html>
