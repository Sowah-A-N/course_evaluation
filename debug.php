<?php

/**
 * System Debug Page
 * Helps diagnose login and access issues
 */

require_once 'config/database.php';
require_once 'config/constants.php';
require_once 'includes/session.php';

start_secure_session();

?>
<!DOCTYPE html>
<html>

<head>
    <title>System Debug</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background: #f5f5f5
        }

        .debug-section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1)
        }

        h2 {
            color: #667eea;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #f0f0f0
        }

        td:first-child {
            font-weight: bold;
            width: 250px
        }

        .ok {
            color: #28a745;
            font-weight: bold
        }

        .error {
            color: #dc3545;
            font-weight: bold
        }

        .warning {
            color: #ffc107;
            font-weight: bold
        }

        pre {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px
        }
    </style>
</head>

<body>

    <h1>🔧 System Debug Information</h1>

    <div class="debug-section">
        <h2>Session Status</h2>
        <table>
            <tr>
                <td>Session Active</td>
                <td class="<?php echo isset($_SESSION['user_id']) ? 'ok' : 'error'; ?>">
                    <?php echo isset($_SESSION['user_id']) ? '✓ YES' : '✗ NO'; ?>
                </td>
            </tr>
            <tr>
                <td>User ID</td>
                <td><?php echo $_SESSION['user_id'] ?? '<span class="error">NOT SET</span>'; ?></td>
            </tr>
            <tr>
                <td>Role ID</td>
                <td><?php echo $_SESSION['role_id'] ?? '<span class="error">NOT SET</span>'; ?></td>
            </tr>
            <tr>
                <td>Role Name</td>
                <td><?php echo isset($_SESSION['role_id']) ? ROLE_NAMES[$_SESSION['role_id']] : '<span class="error">NOT SET</span>'; ?></td>
            </tr>
            <tr>
                <td>Full Name</td>
                <td><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'NOT SET'); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><?php echo htmlspecialchars($_SESSION['email'] ?? 'NOT SET'); ?></td>
            </tr>
            <tr>
                <td>Department ID</td>
                <td><?php echo $_SESSION['department_id'] ?? '<span class="warning">NOT SET (OK for Admin/Quality)</span>'; ?></td>
            </tr>
        </table>
    </div>

    <div class="debug-section">
        <h2>Role Constants</h2>
        <table>
            <?php foreach (ROLE_NAMES as $rid => $rname): ?>
                <tr>
                    <td><?php echo htmlspecialchars($rname); ?></td>
                    <td>Role ID: <strong><?php echo $rid; ?></strong></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="debug-section">
        <h2>Access Tests</h2>
        <table>
            <tr>
                <td>Can Access Admin</td>
                <td class="<?php echo isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_ADMIN ? 'ok' : 'error'; ?>">
                    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_ADMIN): ?>
                        ✓ YES
                    <?php else: ?>
                        ✗ NO (Need role_id = <?php echo ROLE_ADMIN; ?>)
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Can Access HOD</td>
                <td class="<?php echo isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_HOD ? 'ok' : 'error'; ?>">
                    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_HOD): ?>
                        ✓ YES
                    <?php else: ?>
                        ✗ NO (Need role_id = <?php echo ROLE_HOD; ?>)
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Can Access Secretary</td>
                <td class="<?php echo isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_SECRETARY ? 'ok' : 'error'; ?>">
                    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_SECRETARY): ?>
                        ✓ YES
                    <?php else: ?>
                        ✗ NO (Need role_id = <?php echo ROLE_SECRETARY; ?>)
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Can Access Quality</td>
                <td class="<?php echo isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_QUALITY ? 'ok' : 'error'; ?>">
                    <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == ROLE_QUALITY): ?>
                        ✓ YES
                    <?php else: ?>
                        ✗ NO (Need role_id = <?php echo ROLE_QUALITY; ?>)
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="debug-section">
            <h2>Quick Actions</h2>
            <?php if ($_SESSION['role_id'] == ROLE_ADMIN): ?>
                <a href="admin/index.php" class="btn">Go to Admin Dashboard</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == ROLE_HOD): ?>
                <a href="hod/index.php" class="btn">Go to HOD Dashboard</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == ROLE_SECRETARY): ?>
                <a href="secretary/index.php" class="btn">Go to Secretary Dashboard</a>
            <?php endif; ?>
            <?php if ($_SESSION['role_id'] == ROLE_QUALITY): ?>
                <a href="quality/index.php" class="btn">Go to Quality Dashboard</a>
            <?php endif; ?>
            <a href="logout.php" class="btn" style="background:#dc3545">Logout</a>
        </div>
    <?php else: ?>
        <div class="debug-section">
            <h2>Not Logged In</h2>
            <p>You are not currently logged in. Please log in to access the system.</p>
            <a href="login.php" class="btn">Go to Login Page</a>
        </div>
    <?php endif; ?>

    <div class="debug-section">
        <h2>Database Connection</h2>
        <table>
            <tr>
                <td>Connection Status</td>
                <td class="<?php echo mysqli_ping($conn) ? 'ok' : 'error'; ?>">
                    <?php echo mysqli_ping($conn) ? '✓ Connected' : '✗ Not Connected'; ?>
                </td>
            </tr>
            <tr>
                <td>Database Host</td>
                <td><?php echo DB_HOST; ?></td>
            </tr>
            <tr>
                <td>Database Name</td>
                <td><?php echo DB_NAME; ?></td>
            </tr>
        </table>
    </div>

    <div class="debug-section">
        <h2>All Session Data</h2>
        <pre><?php print_r($_SESSION); ?></pre>
    </div>

    <div class="debug-section">
        <h2>Server Path Information</h2>
        <table>
            <tr>
                <td>PHP_SELF</td>
                <td><?php echo $_SERVER['PHP_SELF'] ?? 'NOT SET'; ?></td>
            </tr>
            <tr>
                <td>SCRIPT_NAME</td>
                <td><?php echo $_SERVER['SCRIPT_NAME'] ?? 'NOT SET'; ?></td>
            </tr>
            <tr>
                <td>REQUEST_URI</td>
                <td><?php echo $_SERVER['REQUEST_URI'] ?? 'NOT SET'; ?></td>
            </tr>
        </table>
    </div>

</body>

</html>
