<?php

/**
 * Logout Handler
 * 
 * This script handles user logout by:
 * - Logging the logout action (optional audit)
 * - Destroying the session
 * - Clearing session cookies
 * - Redirecting to login page
 * 
 * Security: This page should not require authentication check
 * since its purpose is to logout users.
 */

// Start session
session_start();

// Include required files
require_once 'config/database.php';
require_once 'config/constants.php';

// Store user_id before destroying session (for audit log)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Optional: Log logout action to audit_logs table
if ($user_id !== null) {
    // Prepare audit log query
    $query = "INSERT INTO " . TABLE_AUDIT_LOGS . " 
              (user_id, action_type, ip_address, user_agent, created_at) 
              VALUES (?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        $action_type = AUDIT_LOGOUT;
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknown';

        mysqli_stmt_bind_param($stmt, "isss", $user_id, $action_type, $ip_address, $user_agent);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Unset all session variables
$_SESSION = array();

// Delete session cookie
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

// Destroy the session
session_destroy();

// Close database connection (optional)
mysqli_close($conn);

// Redirect to login page with logout message
header("Location: login.php?logout=success");
exit();
