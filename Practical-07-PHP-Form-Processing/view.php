<?php
// Practical 7: View Stored Student Records
// Reads from data/students.json

$jsonFile = __DIR__ . '/data/students.json';
$students = [];

if (file_exists($jsonFile)) {
    $content = file_get_contents($jsonFile);
    $data = json_decode($content, true);
    if (is_array($data)) {
        $students = $data;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 7 - Stored Student Records | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="table-container">
    <h2>Stored Student Registrations</h2>

    <div class="nav-links">
      <a href="index.php">&larr; Register New Student</a> |
      <a href="view.php">Refresh List</a> |
      <a href="data/students.csv" download>Download CSV File</a>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
      <div class="alert alert-success">
        <strong>Success!</strong> New student registration recorded successfully.
      </div>
    <?php endif; ?>

    <p>Total Records Found: <strong><?php echo count($students); ?></strong></p>

    <?php if (empty($students)): ?>
      <p style="text-align: center; color: #888; padding: 20px;">
        No student records found yet. <a href="index.php">Click here to register the first student.</a>
      </p>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Roll No</th>
            <th>Email</th>
            <th>Department</th>
            <th>Sem</th>
            <th>Remarks</th>
            <th>Registered At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($students as $index => $row): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['id'] ?? ($index + 1)); ?></td>
              <td><strong><?php echo htmlspecialchars($row['fullName']); ?></strong></td>
              <td><code><?php echo htmlspecialchars($row['rollNo']); ?></code></td>
              <td><?php echo htmlspecialchars($row['email']); ?></td>
              <td><span class="badge"><?php echo htmlspecialchars($row['department']); ?></span></td>
              <td><?php echo htmlspecialchars($row['semester']); ?></td>
              <td><?php echo htmlspecialchars($row['remarks'] ?: '-'); ?></td>
              <td style="font-size: 12px; color: #666;"><?php echo htmlspecialchars($row['timestamp']); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>

  </div>

</body>
</html>
