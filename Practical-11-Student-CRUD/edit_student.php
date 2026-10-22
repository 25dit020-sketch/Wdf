<?php
// Practical 11: Update (Edit Student Details)
session_start();
require_once 'db.php';

$studentId = intval($_GET['id'] ?? 0);

if ($studentId <= 0) {
    header("Location: index.php?err=" . urlencode("Invalid student ID."));
    exit();
}

// Fetch existing record
$fetchStmt = mysqli_prepare($conn, "SELECT s.*, u.name, u.email FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
mysqli_stmt_bind_param($fetchStmt, "i", $studentId);
mysqli_stmt_execute($fetchStmt);
$student = mysqli_fetch_assoc(mysqli_stmt_get_result($fetchStmt));
mysqli_stmt_close($fetchStmt);

if (!$student) {
    header("Location: index.php?err=" . urlencode("Student record not found."));
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $rollNo     = trim($_POST['roll_no'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $semester   = intval($_POST['semester'] ?? 1);
    $cgpa       = floatval($_POST['cgpa'] ?? 0.0);
    $contactNo  = trim($_POST['contact_no'] ?? '');

    if (empty($name) || empty($email) || empty($rollNo) || empty($department) || empty($contactNo)) {
        $error = "Please fill in all mandatory fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check duplicate roll number for other students
        $checkRoll = mysqli_prepare($conn, "SELECT id FROM students WHERE roll_no = ? AND id != ?");
        mysqli_stmt_bind_param($checkRoll, "si", $rollNo, $studentId);
        mysqli_stmt_execute($checkRoll);
        mysqli_stmt_store_result($checkRoll);

        if (mysqli_stmt_num_rows($checkRoll) > 0) {
            $error = "Roll number is already in use by another student.";
        } else {
            mysqli_begin_transaction($conn);
            try {
                // Update User details
                $updateUser = mysqli_prepare($conn, "UPDATE users SET name = ?, email = ? WHERE id = ?");
                mysqli_stmt_bind_param($updateUser, "ssi", $name, $email, $student['user_id']);
                mysqli_stmt_execute($updateUser);

                // Update Student profile
                $updateStud = mysqli_prepare($conn, "UPDATE students SET roll_no = ?, department = ?, semester = ?, cgpa = ?, contact_no = ? WHERE id = ?");
                mysqli_stmt_bind_param($updateStud, "ssidss", $rollNo, $department, $semester, $cgpa, $contactNo, $studentId);
                mysqli_stmt_execute($updateStud);

                mysqli_commit($conn);
                header("Location: index.php?msg=" . urlencode("Student $name ($rollNo) updated successfully."));
                exit();
            } catch (Exception $e) {
                mysqli_rollback($conn);
                $error = "Update failed: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student Record | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="form-container">
    <h2>Edit Student Details</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="edit_student.php?id=<?php echo $studentId; ?>">
      
      <div class="form-group">
        <label>Full Name *</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
      </div>

      <div class="form-group">
        <label>Email Address *</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
      </div>

      <div class="form-group">
        <label>Roll / Enrollment No *</label>
        <input type="text" name="roll_no" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
      </div>

      <div class="form-group">
        <label>Department *</label>
        <select name="department" required>
          <?php 
          $depts = ["Information Technology", "Computer Engineering", "Computer Science", "Electronics & Comm", "Mechanical Engineering"];
          foreach ($depts as $d): ?>
            <option value="<?php echo $d; ?>" <?php echo ($student['department'] === $d) ? 'selected' : ''; ?>><?php echo $d; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div style="display: flex; gap: 15px;">
        <div class="form-group" style="flex:1;">
          <label>Semester *</label>
          <select name="semester" required>
            <?php for($i=1; $i<=8; $i++): ?>
              <option value="<?php echo $i; ?>" <?php echo ($student['semester'] == $i) ? 'selected' : ''; ?>>Semester <?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-group" style="flex:1;">
          <label>CGPA</label>
          <input type="number" step="0.01" min="0" max="10" name="cgpa" value="<?php echo htmlspecialchars($student['cgpa']); ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Contact Number *</label>
        <input type="tel" name="contact_no" value="<?php echo htmlspecialchars($student['contact_no']); ?>" required>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:10px;">Update Student Record</button>
      <div style="text-align:center; margin-top:15px;">
        <a href="index.php" style="color:#003366; text-decoration:none;">&larr; Cancel and Return</a>
      </div>
    </form>
  </div>

</body>
</html>
