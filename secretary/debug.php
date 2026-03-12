<?php

/**
 * Secretary Debug - Check Access
 * Temporary file to debug secretary access issues
 */

require_once '../config/database.php';
require_once '../config/constants.php';
require_once '../includes/session.php';

start_secure_session();

echo "<h1>Secretary Access Debug</h1>";
echo "<pre>";
echo "=== SESSION DATA ===\n";
echo "Logged in: " . (isset($_SESSION['user_id']) ? 'YES' : 'NO') . "\n";
echo "User ID: " . ($_SESSION['user_id'] ?? 'NOT SET') . "\n";
echo "Role ID: " . ($_SESSION['role_id'] ?? 'NOT SET') . "\n";
echo "Role Name: " . (isset($_SESSION['role_id']) && isset(ROLE_NAMES[$_SESSION['role_id']]) ? ROLE_NAMES[$_SESSION['role_id']] : 'NOT SET') . "\n";
echo "Full Name: " . ($_SESSION['full_name'] ?? 'NOT SET') . "\n";
echo "Department ID: " . ($_SESSION['department_id'] ?? 'NOT SET') . "\n";

echo "\n=== ROLE CONSTANTS ===\n";
echo "ROLE_ADMIN: " . ROLE_ADMIN . "\n";
echo "ROLE_HOD: " . ROLE_HOD . "\n";
echo "ROLE_ADVISOR: " . ROLE_ADVISOR . "\n";
echo "ROLE_STUDENT: " . ROLE_STUDENT . "\n";
echo "ROLE_QUALITY: " . (defined('ROLE_QUALITY') ? ROLE_QUALITY : 'NOT DEFINED') . "\n";
echo "ROLE_SECRETARY: " . (defined('ROLE_SECRETARY') ? ROLE_SECRETARY : 'NOT DEFINED') . "\n";

echo "\n=== ACCESS CHECK ===\n";
if (!isset($_SESSION['role_id'])) {
    echo "❌ Not logged in - no role_id in session\n";
} elseif ($_SESSION['role_id'] == ROLE_SECRETARY) {
    echo "✅ Access GRANTED - Role matches ROLE_SECRETARY\n";
} else {
    echo "❌ Access DENIED - Role " . $_SESSION['role_id'] . " does not match ROLE_SECRETARY (" . ROLE_SECRETARY . ")\n";
}

echo "\n=== SOLUTION ===\n";
if (!isset($_SESSION['role_id'])) {
    echo "1. Log in to the system first\n";
    echo "2. <a href='../login.php'>Go to Login Page</a>\n";
} elseif ($_SESSION['role_id'] != ROLE_SECRETARY) {
    echo "Your current role (" . ROLE_NAMES[$_SESSION['role_id']] . ") cannot access secretary pages.\n";
    echo "You need to log in with a Secretary account.\n";
    echo "\nTo fix:\n";
    echo "1. Create a user with role_id = " . ROLE_SECRETARY . " (Secretary) in your database\n";
    echo "2. OR temporarily change this line in secretary files:\n";
    echo "   FROM: if (\$_SESSION['role_id'] != ROLE_SECRETARY)\n";
    echo "   TO:   if (\$_SESSION['role_id'] != " . $_SESSION['role_id'] . ") // Your current role\n";
}

echo "</pre>";
