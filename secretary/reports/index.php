<?php
/**
 * Secretary Reports Index
 * Redirects to department overview report.
 * Role Required: ROLE_SECRETARY
 */

require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';

start_secure_session();
check_login();

if ($_SESSION['role_id'] != ROLE_SECRETARY) {
    header("Location: ../../login.php");
    exit();
}

header("Location: department_overview.php");
exit();
?>
