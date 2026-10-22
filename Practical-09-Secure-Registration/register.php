<?php
// Practical 9: StudentHub Secure Registration Page
session_start();
$errorMessage = $_SESSION['reg_error'] ?? '';
$successMessage = $_SESSION['reg_success'] ?? '';
$formData = $_SESSION['form_data'] ?? [];

// Clear flash session data
unset($_SESSION['reg_error'], $_SESSION['reg_success'], $_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 9 - Student Registration | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="form-box">
    <h2>StudentHub Registration</h2>
    <p class="subtitle">Create your student portal account with university credentials</p>

    <!-- Error Banner -->
    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger">
        <strong>Registration Error:</strong> <?php echo htmlspecialchars($errorMessage); ?>
      </div>
    <?php endif; ?>

    <!-- Success Banner -->
    <?php if (!empty($successMessage)): ?>
      <div class="alert alert-success">
        <strong>Success!</strong> <?php echo htmlspecialchars($successMessage); ?>
        <br><a href="../Practical-10-Secure-Auth-Sessions/login.php" style="color: #155724; font-weight:bold;">Proceed to Login &rarr;</a>
      </div>
    <?php endif; ?>

    <form action="process_register.php" method="POST">
      
      <!-- Full Name -->
      <div class="form-group">
        <label for="fullName">Full Name <span class="required">*</span></label>
        <input type="text" id="fullName" name="fullName" placeholder="e.g. Diya Patel" 
               value="<?php echo htmlspecialchars($formData['fullName'] ?? ''); ?>" required>
      </div>

      <!-- University Email -->
      <div class="form-group">
        <label for="email">University Email <span class="required">*</span></label>
        <input type="email" id="email" name="email" placeholder="e.g. diya.ce@charusat.edu.in" 
               value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
      </div>

      <div class="form-row">
        <!-- Roll Number -->
        <div class="form-col form-group">
          <label for="rollNo">Roll / Enrollment No <span class="required">*</span></label>
          <input type="text" id="rollNo" name="rollNo" placeholder="e.g. 23CE014" 
                 value="<?php echo htmlspecialchars($formData['rollNo'] ?? ''); ?>" required>
        </div>

        <!-- Contact Number -->
        <div class="form-col form-group">
          <label for="contactNo">Mobile Number <span class="required">*</span></label>
          <input type="tel" id="contactNo" name="contactNo" placeholder="10-digit mobile" 
                 value="<?php echo htmlspecialchars($formData['contactNo'] ?? ''); ?>" required>
        </div>
      </div>

      <div class="form-row">
        <!-- Department -->
        <div class="form-col form-group">
          <label for="department">Department <span class="required">*</span></label>
          <select id="department" name="department" required>
            <option value="">-- Select --</option>
            <option value="Information Technology" <?php echo (($formData['department'] ?? '') === 'Information Technology') ? 'selected' : ''; ?>>IT</option>
            <option value="Computer Engineering" <?php echo (($formData['department'] ?? '') === 'Computer Engineering') ? 'selected' : ''; ?>>CE</option>
            <option value="Computer Science" <?php echo (($formData['department'] ?? '') === 'Computer Science') ? 'selected' : ''; ?>>CSE</option>
            <option value="Electronics & Comm" <?php echo (($formData['department'] ?? '') === 'Electronics & Comm') ? 'selected' : ''; ?>>EC</option>
            <option value="Mechanical Engineering" <?php echo (($formData['department'] ?? '') === 'Mechanical Engineering') ? 'selected' : ''; ?>>ME</option>
          </select>
        </div>

        <!-- Semester -->
        <div class="form-col form-group">
          <label for="semester">Semester <span class="required">*</span></label>
          <select id="semester" name="semester" required>
            <option value="">-- Sem --</option>
            <?php for ($s = 1; $s <= 8; $s++): ?>
              <option value="<?php echo $s; ?>" <?php echo (($formData['semester'] ?? '') == $s) ? 'selected' : ''; ?>>Sem <?php echo $s; ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>

      <div class="form-row">
        <!-- Password -->
        <div class="form-col form-group">
          <label for="password">Password <span class="required">*</span></label>
          <input type="password" id="password" name="password" placeholder="Min 8 characters" required>
        </div>

        <!-- Confirm Password -->
        <div class="form-col form-group">
          <label for="confirmPassword">Confirm Password <span class="required">*</span></label>
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Re-type password" required>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn-primary">Register Account</button>

      <div class="login-link">
        Already have an account? <a href="../Practical-10-Secure-Auth-Sessions/login.php">Log In Here</a>
      </div>

    </form>
  </div>

</body>
</html>
