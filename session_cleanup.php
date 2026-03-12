<?php

/**
 * Session Cleanup Utility
 *
 * This script helps fix the "two session cookies" problem by:
 * 1. Clearing both PHPSESSID and COURSE_EVAL_SESSION cookies
 * 2. Starting fresh with only COURSE_EVAL_SESSION
 *
 * USE THIS ONCE to clear duplicate sessions, then delete this file.
 */

// Clear PHPSESSID cookie if it exists
if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', '', time() - 3600, '/');
    $cleared_phpsessid = true;
} else {
    $cleared_phpsessid = false;
}

// Clear COURSE_EVAL_SESSION cookie if it exists
if (isset($_COOKIE['COURSE_EVAL_SESSION'])) {
    setcookie('COURSE_EVAL_SESSION', '', time() - 3600, '/');
    $cleared_course_eval = true;
} else {
    $cleared_course_eval = false;
}

// Destroy any active session
if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Session Cleanup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }

        .cleanup-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #667eea;
            border-bottom: 3px solid #667eea;
            padding-bottom: 15px;
        }

        .status {
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }

        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .info {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .warning {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            margin: 10px 5px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-danger {
            background: #dc3545;
        }

        code {
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 3px;
            font-family: monospace;
        }

        ol {
            line-height: 2;
        }
    </style>
</head>

<body>

    <div class="cleanup-box">
        <h1>🔧 Session Cleanup Complete</h1>

        <div class="status success">
            <strong>✓ Cleanup Completed Successfully!</strong>
        </div>

        <h2>What Was Fixed:</h2>
        <div class="status info">
            <?php if ($cleared_phpsessid): ?>
                ✓ Cleared <code>PHPSESSID</code> cookie (old default session)<br>
            <?php else: ?>
                ℹ️ <code>PHPSESSID</code> cookie was not found<br>
            <?php endif; ?>

            <?php if ($cleared_course_eval): ?>
                ✓ Cleared <code>COURSE_EVAL_SESSION</code> cookie<br>
            <?php else: ?>
                ℹ️ <code>COURSE_EVAL_SESSION</code> cookie was not found<br>
            <?php endif; ?>

            ✓ Destroyed any active sessions<br>
            ✓ System is now ready for fresh login
        </div>

        <h2>What Caused the Problem:</h2>
        <div class="status warning">
            <strong>Problem:</strong> Your <code>login.php</code>, <code>index.php</code>, and <code>logout.php</code>
            were calling <code>session_start()</code> directly instead of using <code>start_secure_session()</code>.

            <p><strong>Result:</strong> Two session cookies were created:</p>
            <ul>
                <li><code>PHPSESSID</code> - Default PHP session (from direct session_start)</li>
                <li><code>COURSE_EVAL_SESSION</code> - Custom session (from start_secure_session)</li>
            </ul>

            <p>This caused session data conflicts and login issues.</p>
        </div>

        <h2>✅ Fixes Applied:</h2>
        <ol>
            <li><strong>Fixed login.php</strong> - Now uses <code>start_secure_session()</code></li>
            <li><strong>Fixed index.php</strong> - Now uses <code>start_secure_session()</code></li>
            <li><strong>Fixed logout.php</strong> - Now uses <code>start_secure_session()</code> and <code>logout_user()</code></li>
            <li><strong>Fixed session.php</strong> - Disabled strict user agent validation that was clearing sessions</li>
            <li><strong>Cleared duplicate cookies</strong> - This cleanup script</li>
        </ol>

        <h2>🚀 Next Steps:</h2>
        <div class="status info">
            <ol>
                <li><strong>Close all browser tabs</strong> of this application</li>
                <li><strong>Clear browser cookies</strong> manually (optional but recommended):
                    <ul>
                        <li>Chrome: Settings → Privacy → Clear browsing data → Cookies</li>
                        <li>Firefox: Settings → Privacy → Clear Data → Cookies</li>
                    </ul>
                </li>
                <li><strong>Delete this file</strong> (<code>session_cleanup.php</code>) - it's no longer needed</li>
                <li><strong>Try logging in again</strong> - should work now!</li>
            </ol>
        </div>

        <h2>Testing Your Login:</h2>
        <div style="text-align: center; margin: 30px 0;">
            <a href="login.php" class="btn btn-success">Go to Login Page</a>
            <a href="test_access.php" class="btn">Test Access Tool</a>
            <a href="debug.php" class="btn">Debug Info</a>
        </div>

        <h2>⚠️ Important Reminder:</h2>
        <div class="status warning">
            <strong>Delete this file after use!</strong><br>
            This cleanup script should only be run once. After your sessions are working properly,
            delete <code>session_cleanup.php</code> from your server.
        </div>

        <h2>Still Having Issues?</h2>
        <p>If you're still experiencing login problems after following all steps:</p>
        <ul>
            <li>Check that you have a user account with the correct role_id in the database</li>
            <li>Use <a href="test_access.php" style="color:#667eea">test_access.php</a> to verify your role</li>
            <li>Use <a href="debug.php" style="color:#667eea">debug.php</a> to see session data</li>
            <li>Check browser console for JavaScript errors</li>
            <li>Verify your database connection is working</li>
        </ul>
    </div>

</body>

</html>
