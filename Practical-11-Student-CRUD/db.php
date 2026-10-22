<?php
// Practical 11: Database Connection Helper
// Beginner-friendly MySQLi Procedural Connection

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'studenthub');

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("<div style='font-family:Arial; padding:20px; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb;'>
            <strong>Database Connection Error:</strong> " . mysqli_connect_error() . "<br>
            Please check that MySQL is running and the database <code>studenthub</code> exists.
         </div>");
}

mysqli_set_charset($conn, "utf8mb4");
?>
