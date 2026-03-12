<?php

/**
 * HOD Lecturers Index
 *
 * Redirects to lecturer list page.
 *
 * Role Required: ROLE_HOD
 */

require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';

start_secure_session();
check_login();

if ($_SESSION['role_id'] != ROLE_HOD) {
    header("Location: ../../login.php");
    exit();
}

header("Location: list.php");
exit();
