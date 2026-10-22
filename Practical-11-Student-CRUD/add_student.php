<?php
// Practical 11: Create (Add New Student)
session_start();
require_once 'db.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name       = trim($_POST['name'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $rollNo     = trim($_POST['roll_no'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $semester   = intval($_POST['semester'] ?? 1);
    $cgpa       = floatval($_POST['cgpa'] ?? 0.0);
    $contactNo  = trim($_POST['contact_no'] ?? '');

    // Validation
    if (empty($name) || empty($email) || empty($rollNo) || empty($department) || empty($contactNo)) {
        $error = "Please fill in all mandatory fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Check duplicate email
        $checkEmail = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($checkEmail, "s", $email);
        mysqli_stmt_execute($checkEmail);
        mysqli_stmt_store_result($checkEmail);
        
        if (mysqli_stmt_num_rows($checkEmail) > 0) {
            $error = "Email is already registered by another user.";
        } else {
            // Check duplicate roll number
            $checkRoll = mysqli_prepare($conn, "SELECT id FROM students WHERE roll_no = ?");
            mysqli_stmt_bind_param($checkRoll, "s", $rollNo);
            mysqli_stmt_execute($checkRoll);
            mysqli_stmt_store_result($checkRoll);

            if (mysqli_stmt_num_rows($checkRoll) > 0) {
                $error = "Roll number is already assigned to another student.";
            } else {
                // Default initial password: Student@123
                $hashedPass = password_hash("Student@123", PASSWORD_DEFAULT);

                mysqli_begin_transaction($conn);
                try {
                    // Insert User
                    $stmtUser = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'student', 'active')");
                    mysqli_stmt_bind_param($stmtUser, "sss", $name, $email, $hashedPass);
                    mysqli_stmt_execute($stmtUser);
                    $newUserId = mysqli_insert_id($conn);

                    // Insert Student Profile
                    $stmtStud = mysqli_prepare($conn, "INSERT INTO students (user_id, roll_no, department, semester, cgpa, contact_no) VALUES (?, ?, ?, ?, ?, ?)");
                    mysqli_stmt_bind_param($stmtStud, "issids", $newUserId, $rollNo, $department, $semester, $cgpa, $contactNo);
                    mysqli_stmt_execute($stmtStud);

                    mysqli_commit($conn);
                    header("Location: index.php?msg=" . urlencode("Student $name ($rollNo) added successfully. Default password is 'Student@123'."));
                    exit();
                } catch (Exception $e) {
                    mysqli_rollback($conn);
                    $error = "Failed to add student: " . $e->getMessage();
                }
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
  <title>Add Student Record | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="form-container">
    <h2>Add New Student Record</h2>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="add_student.php">
      
      <div class="form-group">
        <label>Full Name *</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label>Email Address *</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label>Roll / Enrollment No *</label>
        <input type="text" name="roll_no" value="<?php echo htmlspecialchars($_POST['roll_no'] ?? ''); ?>" required>
      </div>

      <div class="form-group">
        <label>Department *</label>
        <select name="department" required>
          <option value="">-- Select Department --</option>
          <option value="Information Technology">Information Technology</option>
          <option value="Computer Engineering">Computer Engineering</option>
          <option value="Computer Science">Computer Science &amp; Engg</option>
          <option value="Electronics & Comm">Electronics &amp; Comm</option>
          <option value="Mechanical Engineering">Mechanical Engineering</option>
        </select>
      </div>

      <div style="display: flex; gap: 15px;">
        <div class="form-group" style="flex:1;">
          <label>Semester *</label>
          <select name="semester" required>
            <?php for($i=1; $i<=8; $i++): ?>
              <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
        </div>

        <div class="form-group" style="flex:1;">
          <label>CGPA</label>
          <input type="number" step="0.01" min="0" max="10" name="cgpa" placeholder="e.g. 8.50" value="<?php echo htmlspecialchars($_POST['cgpa'] ?? '0.00'); ?>">
        </div>
      </div>

      <div class="form-group">
        <label>Contact Number *</label>
        <input type="tel" name="contact_no" placeholder="10-digit mobile" value="<?php echo htmlspecialchars($_POST['contact_no'] ?? ''); ?>" required>
      </div>

      <button type="submit" class="btn btn-primary" style="width:100%; padding:10px;">Save Student Record</button>
      <div style="text-align:center; margin-top:15px;">
        <a href="index.php" style="color:#003366; text-decoration:none;">&larr; Cancel and Return</a>
      </div>
    </form>
  </div>

</body>
</html>
