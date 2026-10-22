<?php
// Practical 8: Database Connection File (db.php)
// Beginner Friendly MySQLi Procedural Connection

// Database configuration settings
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'studenthub');

// Attempt to connect to MySQL database
$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if connection succeeded
if (!$conn) {
    // If connection failed, show helpful error message
    die("<div style='font-family:Arial; padding:20px; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; border-radius:5px; max-width:600px; margin:30px auto;'>
            <h3>Database Connection Failed!</h3>
            <p><strong>Error:</strong> " . mysqli_connect_error() . "</p>
            <p><strong>Checklist:</strong></p>
            <ul>
                <li>Is MySQL server running in XAMPP?</li>
                <li>Has the database <code>studenthub</code> been created using <code>schema.sql</code>?</li>
                <li>Are username and password correct (default: user='root', pass='')?</li>
            </ul>
         </div>");
}

// Set character set to UTF-8
mysqli_set_charset($conn, "utf8mb4");
?>
