<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 7 - PHP Form Processing | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    <h2>Student Registration (File Storage)</h2>

    <div class="nav-links">
      <a href="index.php">New Registration</a> |
      <a href="view.php">View Stored Records</a>
    </div>

    <!-- Feedback Alerts -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
      <div class="alert alert-success">
        <strong>Success!</strong> Student record successfully validated and saved to both CSV and JSON files.
      </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger">
        <strong>Error:</strong> <?php echo htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>

    <!-- Student Registration Form -->
    <form action="process.php" method="POST">
      
      <!-- Full Name -->
      <div class="form-group">
        <label for="fullName">Full Name <span class="required">*</span></label>
        <input type="text" id="fullName" name="fullName" placeholder="e.g. Diya Patel" required>
      </div>

      <!-- Roll Number -->
      <div class="form-group">
        <label for="rollNo">Roll Number <span class="required">*</span></label>
        <input type="text" id="rollNo" name="rollNo" placeholder="e.g. 23IT045" required>
      </div>

      <!-- Email Address -->
      <div class="form-group">
        <label for="email">Email Address <span class="required">*</span></label>
        <input type="email" id="email" name="email" placeholder="e.g. diya.it@charusat.edu.in" required>
      </div>

      <!-- Department -->
      <div class="form-group">
        <label for="department">Department <span class="required">*</span></label>
        <select id="department" name="department" required>
          <option value="">-- Select Department --</option>
          <option value="Information Technology">Information Technology (IT)</option>
          <option value="Computer Engineering">Computer Engineering (CE)</option>
          <option value="Computer Science">Computer Science &amp; Engg (CSE)</option>
          <option value="Electronics & Comm">Electronics &amp; Comm (EC)</option>
          <option value="Mechanical Engineering">Mechanical Engineering (ME)</option>
        </select>
      </div>

      <!-- Semester -->
      <div class="form-group">
        <label for="semester">Current Semester <span class="required">*</span></label>
        <select id="semester" name="semester" required>
          <option value="">-- Select Semester --</option>
          <option value="1">Semester 1</option>
          <option value="2">Semester 2</option>
          <option value="3">Semester 3</option>
          <option value="4">Semester 4</option>
          <option value="5">Semester 5</option>
          <option value="6">Semester 6</option>
          <option value="7">Semester 7</option>
          <option value="8">Semester 8</option>
        </select>
      </div>

      <!-- Remarks / Interests -->
      <div class="form-group">
        <label for="remarks">Interests / Remarks</label>
        <textarea id="remarks" name="remarks" placeholder="Web Development, Cyber Security, AI, etc."></textarea>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn-submit">Submit &amp; Save Record</button>

    </form>
  </div>

</body>
</html>
