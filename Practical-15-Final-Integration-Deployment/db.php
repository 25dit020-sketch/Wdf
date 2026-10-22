<?php
// Practical 15: Database Connection Helper
// Beginner-friendly MySQLi Procedural Connection

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'studenthub');

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    $dbConnected = false;
    $dbError = mysqli_connect_error();
} else {
    $dbConnected = true;
    mysqli_set_charset($conn, "utf8mb4");
}
?>
