<?php
// Practical 9: Process Secure Registration
// Beginner-friendly Procedural PHP with Prepared Statements

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}

// 1. Collect and Sanitize Form Inputs
$fullName        = trim($_POST['fullName'] ?? '');
$email           = trim($_POST['email'] ?? '');
$rollNo          = trim($_POST['rollNo'] ?? '');
$contactNo       = trim($_POST['contactNo'] ?? '');
$department      = trim($_POST['department'] ?? '');
$semester        = intval($_POST['semester'] ?? 1);
$password        = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

// Retain form data in session for sticky forms
$_SESSION['form_data'] = [
    'fullName'   => $fullName,
    'email'      => $email,
    'rollNo'     => $rollNo,
    'contactNo'  => $contactNo,
    'department' => $department,
    'semester'   => $semester
];

// 2. Validation Checks
if (empty($fullName) || empty($email) || empty($rollNo) || empty($contactNo) || empty($department) || empty($password)) {
    $_SESSION['reg_error'] = "All mandatory fields marked with * must be filled.";
    header("Location: register.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['reg_error'] = "Please enter a valid university email address.";
    header("Location: register.php");
    exit();
}

if (strlen($password) < 8) {
    $_SESSION['reg_error'] = "Password must be at least 8 characters long.";
    header("Location: register.php");
    exit();
}

if ($password !== $confirmPassword) {
    $_SESSION['reg_error'] = "Password and Confirm Password do not match.";
    header("Location: register.php");
    exit();
}

// 3. Duplicate Email Check in `users` table
$checkEmailStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($checkEmailStmt, "s", $email);
mysqli_stmt_execute($checkEmailStmt);
mysqli_stmt_store_result($checkEmailStmt);

if (mysqli_stmt_num_rows($checkEmailStmt) > 0) {
    mysqli_stmt_close($checkEmailStmt);
    $_SESSION['reg_error'] = "An account with email '$email' is already registered. Please log in.";
    header("Location: register.php");
    exit();
}
mysqli_stmt_close($checkEmailStmt);

// 4. Duplicate Roll Number Check in `students` table
$checkRollStmt = mysqli_prepare($conn, "SELECT id FROM students WHERE roll_no = ?");
mysqli_stmt_bind_param($checkRollStmt, "s", $rollNo);
mysqli_stmt_execute($checkRollStmt);
mysqli_stmt_store_result($checkRollStmt);

if (mysqli_stmt_num_rows($checkRollStmt) > 0) {
    mysqli_stmt_close($checkRollStmt);
    $_SESSION['reg_error'] = "A student with Roll No '$rollNo' already exists.";
    header("Location: register.php");
    exit();
}
mysqli_stmt_close($checkRollStmt);

// 5. Password Hashing using Bcrypt (PASSWORD_DEFAULT)
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 6. Database Transaction: Insert into `users` and `students`
mysqli_begin_transaction($conn);

try {
    // A. Insert into `users`
    $userStmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'student', 'active')");
    mysqli_stmt_bind_param($userStmt, "sss", $fullName, $email, $hashedPassword);
    mysqli_stmt_execute($userStmt);
    $newUserId = mysqli_insert_id($conn);
    mysqli_stmt_close($userStmt);

    // B. Insert into `students`
    $studentStmt = mysqli_prepare($conn, "INSERT INTO students (user_id, roll_no, department, semester, contact_no) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($studentStmt, "issis", $newUserId, $rollNo, $department, $semester, $contactNo);
    mysqli_stmt_execute($studentStmt);
    mysqli_stmt_close($studentStmt);

    // Commit both inserts
    mysqli_commit($conn);

    // Clear sticky form data
    unset($_SESSION['form_data']);

    $_SESSION['reg_success'] = "Registration successful for $fullName ($rollNo)! You can now log in.";
    header("Location: register.php");
    exit();

} catch (Exception $e) {
    // Rollback if any insert fails
    mysqli_rollback($conn);
    $_SESSION['reg_error'] = "Registration failed due to a database error: " . $e->getMessage();
    header("Location: register.php");
    exit();
}
?>
