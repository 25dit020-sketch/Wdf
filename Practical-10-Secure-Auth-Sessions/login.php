<?php
// Practical 10: Secure Login Page
session_start();

// If user is already logged in, redirect them immediately to their dashboard
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        header("Location: student_dashboard.php");
        exit();
    }
}

$errorMessage = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Practical 10 - User Login | StudentHub</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="login-box">
    <h2>StudentHub Portal</h2>
    <p class="subtitle">Enter your credentials to access your portal</p>

    <!-- Error message banner -->
    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger">
        <strong>Login Failed:</strong> <?php echo htmlspecialchars($errorMessage); ?>
      </div>
    <?php endif; ?>

    <!-- Logged out notice -->
    <?php if (isset($_GET['logged_out'])): ?>
      <div class="alert alert-success">
        You have been safely logged out of your session.
      </div>
    <?php endif; ?>

    <!-- Unauthorized notice -->
    <?php if (isset($_GET['error']) && $_GET['error'] === 'unauthorized'): ?>
      <div class="alert alert-danger">
        Access denied! You must log in with appropriate role permissions to view that page.
      </div>
    <?php endif; ?>

    <form action="process_login.php" method="POST">
      
      <!-- Email Address -->
      <div class="form-group">
        <label for="email">University Email</label>
        <input type="email" id="email" name="email" placeholder="e.g. admin@charusat.edu.in" required>
      </div>

      <!-- Password -->
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn-primary">Sign In to Account</button>

    </form>

    <div style="text-align: center; margin-top: 15px; font-size: 13px;">
      Don't have an account? <a href="../Practical-09-Secure-Registration/register.php" style="color: #003366; font-weight:bold;">Register Here</a>
    </div>

    <!-- Demo Accounts Quick Reference -->
    <div class="demo-accounts">
      <h4>Demo Credentials for Testing:</h4>
      <strong>Admin:</strong> <code>admin@charusat.edu.in</code> | Pass: <code>Admin@123</code><br>
      <strong>Student:</strong> <code>aarav.it@charusat.edu.in</code> | Pass: <code>Student@123</code>
    </div>

  </div>

</body>
</html>
