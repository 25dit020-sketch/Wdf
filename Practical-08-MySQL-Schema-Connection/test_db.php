<?php
// Practical 8: Connection Diagnostics & Table Verifier
// Checks MySQL connection and verifies all StudentHub tables

require_once 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 8 - Database Diagnostic &amp; Table Status | StudentHub</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f7f6;
      margin: 0;
      padding: 30px;
    }
    .card {
      max-width: 750px;
      margin: 0 auto;
      background: white;
      padding: 25px 30px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    h2 {
      color: #003366;
      border-bottom: 2px solid #003366;
      padding-bottom: 10px;
      margin-top: 0;
    }
    .status-badge {
      display: inline-block;
      padding: 6px 14px;
      border-radius: 4px;
      font-weight: bold;
      color: white;
      background-color: #28a745;
      margin-bottom: 15px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #003366;
      color: white;
    }
    tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    .info-list {
      background: #eef4f8;
      padding: 12px 18px;
      border-radius: 4px;
      margin-bottom: 20px;
      font-size: 14px;
    }
  </style>
</head>
<body>

  <div class="card">
    <h2>StudentHub - Database Diagnostic Tool</h2>
    
    <div class="status-badge">
      &#10004; MySQL Database Connected Successfully!
    </div>

    <div class="info-list">
      <strong>Host:</strong> <?php echo DB_HOST; ?> &nbsp;|&nbsp;
      <strong>Database:</strong> <?php echo DB_NAME; ?> &nbsp;|&nbsp;
      <strong>MySQL Server Version:</strong> <?php echo mysqli_get_server_info($conn); ?> &nbsp;|&nbsp;
      <strong>PHP Version:</strong> <?php echo phpversion(); ?>
    </div>

    <h3>Verified Database Tables</h3>
    <table>
      <thead>
        <tr>
          <th>Table Name</th>
          <th>Purpose</th>
          <th>Total Records</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $tables = [
            'users'               => 'Authentication & User Roles (Admin / Student)',
            'students'            => 'Extended Student Academic Profiles & Contacts',
            'events'              => 'Campus Events, Workshops, and Sports Meetups',
            'event_registrations' => 'Junction Table for Event RSVPs'
        ];

        foreach ($tables as $table => $purpose) {
            $countQuery = mysqli_query($conn, "SELECT COUNT(*) as total FROM `$table`");
            if ($countQuery) {
                $row = mysqli_fetch_assoc($countQuery);
                $total = $row['total'];
                $statusHtml = '<span style="color: green; font-weight:bold;">Active (' . $total . ' rows)</span>';
            } else {
                $statusHtml = '<span style="color: red; font-weight:bold;">Table Missing!</span>';
                $total = '0';
            }
        ?>
        <tr>
          <td><code><?php echo $table; ?></code></td>
          <td><?php echo $purpose; ?></td>
          <td><?php echo $total; ?></td>
          <td><?php echo $statusHtml; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>

    <div style="margin-top: 25px; text-align: center;">
      <a href="test_db.php" style="display:inline-block; padding:8px 16px; background:#003366; color:white; text-decoration:none; border-radius:4px;">Re-run Diagnostics</a>
    </div>

  </div>

</body>
</html>
