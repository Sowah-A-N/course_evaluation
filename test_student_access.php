<?php

/**
 * Test Student Access - Step by Step
 */
require_once 'config/constants.php';
require_once 'includes/session.php';

start_secure_session();

echo "<h1>Step-by-Step Student Access Test</h1>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;} .step{background:white;padding:15px;margin:10px 0;border-left:4px solid #667eea;} .ok{color:#28a745;font-weight:bold;} .error{color:#dc3545;font-weight:bold;}</style>";

echo "<div class='step'><h2>Step 1: Session Check</h2>";
echo "Session ID: " . session_id() . "<br>";
echo "User ID in session: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '<span class="error">NOT SET</span>') . "<br>";
echo "Role ID in session: " . (isset($_SESSION['role_id']) ? $_SESSION['role_id'] : '<span class="error">NOT SET</span>') . "<br>";
echo "</div>";

echo "<div class='step'><h2>Step 2: Constants Check</h2>";
echo "ROLE_STUDENT constant: " . ROLE_STUDENT . "<br>";
echo "Session role_id value: " . ($_SESSION['role_id'] ?? 'NOT SET') . "<br>";
echo "Session role_id TYPE: " . gettype($_SESSION['role_id'] ?? null) . "<br>";
echo "ROLE_STUDENT TYPE: " . gettype(ROLE_STUDENT) . "<br>";
echo "</div>";

echo "<div class='step'><h2>Step 3: Comparison Test</h2>";
if (isset($_SESSION['role_id'])) {
    $role = $_SESSION['role_id'];
    echo "Testing: \$_SESSION['role_id'] ({$role}) == ROLE_STUDENT (" . ROLE_STUDENT . ")<br>";
    echo "Result: " . ($role == ROLE_STUDENT ? '<span class="ok">TRUE ✓</span>' : '<span class="error">FALSE ✗</span>') . "<br>";
    echo "Strict comparison (===): " . ($role === ROLE_STUDENT ? '<span class="ok">TRUE ✓</span>' : '<span class="error">FALSE ✗</span>') . "<br>";

    if ($role != ROLE_STUDENT) {
        echo "<br><span class='error'>⚠️ ROLE MISMATCH DETECTED!</span><br>";
        echo "This would trigger: header('Location: ../index.php');<br>";
    }
} else {
    echo "<span class='error'>role_id not set in session!</span>";
}
echo "</div>";

echo "<div class='step'><h2>Step 4: What student/index.php Will Do</h2>";
echo "<pre>";
echo "// From student/index.php:\n";
echo "if (\$_SESSION['role_id'] != ROLE_STUDENT) {\n";
echo "    // This condition evaluates to: ";
if (isset($_SESSION['role_id'])) {
    echo ($_SESSION['role_id'] != ROLE_STUDENT ? 'TRUE (will redirect)' : 'FALSE (will show dashboard)');
} else {
    echo "TRUE (will redirect - no role_id)";
}
echo "\n}\n";
echo "</pre>";
echo "</div>";

echo "<div class='step'><h2>Step 5: Try Accessing Student Dashboard</h2>";
if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_STUDENT) {
    echo "<p class='ok'>✓ Your role matches! You should be able to access the student dashboard.</p>";
    echo "<a href='student/index.php' style='display:inline-block;padding:10px 20px;background:#667eea;color:white;text-decoration:none;border-radius:5px;'>Go to Student Dashboard</a>";
} else {
    echo "<p class='error'>✗ Role mismatch or not set. You won't be able to access student dashboard.</p>";
    if (isset($_SESSION['role_id'])) {
        echo "<p>Your role is: {$_SESSION['role_id']} but need: " . ROLE_STUDENT . "</p>";
    }
}
echo "</div>";

echo "<div class='step'><h2>Debug: Full Session Data</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo "</div>";
