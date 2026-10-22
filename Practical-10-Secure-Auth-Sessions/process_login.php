<?php
// Practical 10: Secure Login Processor
// Authenticates credentials, regenerates session, and applies RBAC

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// 1. Basic validation
if (empty($email) || empty($password)) {
    $_SESSION['login_error'] = "Please enter both email and password.";
    header("Location: login.php");
    exit();
}

// 2. Fetch User Record via Prepared Statement
$stmt = mysqli_prepare($conn, "SELECT id, name, email, password, role, status FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    // 3. Verify Password Hash using Bcrypt
    if (password_verify($password, $user['password'])) {
        
        // 4. Check if account is active
        if ($user['status'] !== 'active') {
            $_SESSION['login_error'] = "Your account is deactivated. Contact system administrator.";
            header("Location: login.php");
            exit();
        }

        // 5. Session Fixation Protection: Regenerate session ID
        session_regenerate_id(true);

        // 6. Set Session Variables
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['logged_in']  = true;
        $_SESSION['login_time'] = time();

        mysqli_stmt_close($stmt);

        // 7. Role-Based Redirection
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: student_dashboard.php");
        }
        exit();

    } else {
        // Password mismatch (Use generic message to prevent account enumeration)
        $_SESSION['login_error'] = "Invalid email or password combination.";
        header("Location: login.php");
        exit();
    }
} else {
    // User not found (Generic message)
    $_SESSION['login_error'] = "Invalid email or password combination.";
    header("Location: login.php");
    exit();
}
?>
