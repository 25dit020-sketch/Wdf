<?php
// Practical 12: Delete Event and Remove Poster Image File
session_start();
require_once 'db.php';

$eventId = intval($_GET['id'] ?? 0);
if ($eventId <= 0) {
    header("Location: index.php?err=" . urlencode("Invalid event ID."));
    exit();
}

// 1. Fetch poster image filename
$stmt = mysqli_prepare($conn, "SELECT title, poster_image FROM events WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $eventId);
mysqli_stmt_execute($stmt);
$event = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if (!$event) {
    header("Location: index.php?err=" . urlencode("Event not found."));
    exit();
}

// 2. Delete database record
$delStmt = mysqli_prepare($conn, "DELETE FROM events WHERE id = ?");
mysqli_stmt_bind_param($delStmt, "i", $eventId);

if (mysqli_stmt_execute($delStmt)) {
    mysqli_stmt_close($delStmt);

    // 3. Delete physical image file from uploads folder if not default
    if (!empty($event['poster_image']) && $event['poster_image'] !== 'default_event.svg') {
        $filePath = __DIR__ . '/uploads/' . $event['poster_image'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    header("Location: index.php?msg=" . urlencode("Event '" . $event['title'] . "' and its poster image deleted successfully."));
    exit();
} else {
    $err = mysqli_error($conn);
    mysqli_stmt_close($delStmt);
    header("Location: index.php?err=" . urlencode("Failed to delete event: " . $err));
    exit();
}
?>
