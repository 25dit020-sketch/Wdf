<?php
// Practical 11: Delete Student Record
session_start();
require_once 'db.php';

$studentId = intval($_GET['id'] ?? 0);

if ($studentId <= 0) {
    header("Location: index.php?err=" . urlencode("Invalid student ID."));
    exit();
}

// 1. Fetch user_id and name for reference
$findStmt = mysqli_prepare($conn, "SELECT s.user_id, u.name, s.roll_no FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
mysqli_stmt_bind_param($findStmt, "i", $studentId);
mysqli_stmt_execute($findStmt);
$student = mysqli_fetch_assoc(mysqli_stmt_get_result($findStmt));
mysqli_stmt_close($findStmt);

if (!$student) {
    header("Location: index.php?err=" . urlencode("Student record not found."));
    exit();
}

// 2. Delete user account (Foreign Key ON DELETE CASCADE automatically deletes student profile and event registrations)
$deleteStmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($deleteStmt, "i", $student['user_id']);

if (mysqli_stmt_execute($deleteStmt)) {
    mysqli_stmt_close($deleteStmt);
    header("Location: index.php?msg=" . urlencode("Student " . $student['name'] . " (" . $student['roll_no'] . ") deleted successfully."));
    exit();
} else {
    $err = mysqli_error($conn);
    mysqli_stmt_close($deleteStmt);
    header("Location: index.php?err=" . urlencode("Delete failed: " . $err));
    exit();
}
?>
