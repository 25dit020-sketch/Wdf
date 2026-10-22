<?php
// Practical 10: Secure Session Termination (Logout)

session_start();

// 1. Unset all session variables
$_SESSION = array();

// 2. Delete the session cookie from client browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destroy session on server
session_destroy();

// 4. Redirect to login screen with logged out notice
header("Location: login.php?logged_out=1");
exit();
?>
