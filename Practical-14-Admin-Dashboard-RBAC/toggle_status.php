<?php
// Practical 14: Toggle User Account Status (Active / Inactive)
session_start();

// Strict Access Control: Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../Practical-10-Secure-Auth-Sessions/login.php?error=unauthorized");
    exit();
}

require_once 'db.php';

$userId = intval($_GET['id'] ?? 0);
$action = trim($_GET['action'] ?? '');

if ($userId <= 0 || !in_array($action, ['activate', 'deactivate'])) {
    header("Location: dashboard.php?err=" . urlencode("Invalid request parameters."));
    exit();
}

// Self-deactivation prevention check
if ($userId == $_SESSION['user_id']) {
    header("Location: dashboard.php?err=" . urlencode("Security restriction: You cannot deactivate your own administrative account."));
    exit();
}

$newStatus = ($action === 'activate') ? 'active' : 'inactive';

$stmt = mysqli_prepare($conn, "UPDATE users SET status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $newStatus, $userId);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: dashboard.php?msg=" . urlencode("User account #$userId status updated to '$newStatus'."));
    exit();
} else {
    $err = mysqli_error($conn);
    mysqli_stmt_close($stmt);
    header("Location: dashboard.php?err=" . urlencode("Failed to update status: " . $err));
    exit();
}
?>
