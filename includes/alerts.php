<?php
/**
 * Alert/Notification Functions
 * 
 * This file provides functions to display styled alert messages.
 * 
 * Functions:
 * - show_alert() - Display a styled alert message
 * - success_alert() - Display success message
 * - error_alert() - Display error message
 * - warning_alert() - Display warning message
 * - info_alert() - Display info message
 * - display_validation_errors() - Display form validation errors
 * - alert_box() - Display alert with custom icon
 * 
 * USAGE:
 * require_once 'includes/alerts.php';
 * show_alert('Record saved successfully!', 'success');
 */

/**
 * Show Alert Message
 * 
 * Displays a styled alert message box.
 * 
 * @param string $message The message to display
 * @param string $type Alert type: 'success', 'error', 'warning', 'info'
 * @param bool $dismissible Whether alert can be closed (default: true)
 * @param bool $echo Whether to echo or return (default: true)
 * @return string|void HTML alert box
 */
function show_alert($message, $type = 'info', $dismissible = true, $echo = true) {
    // Sanitize message
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    
    // Determine colors and icons based on type
    $colors = [
        'success' => ['bg' => '#d4edda', 'border' => '#c3e6cb', 'text' => '#155724', 'icon' => '✓'],
        'error' => ['bg' => '#f8d7da', 'border' => '#f5c6cb', 'text' => '#721c24', 'icon' => '✕'],
        'warning' => ['bg' => '#fff3cd', 'border' => '#ffeaa7', 'text' => '#856404', 'icon' => '⚠'],
        'info' => ['bg' => '#d1ecf1', 'border' => '#bee5eb', 'text' => '#0c5460', 'icon' => 'ℹ']
    ];
    
    // Get colors for the specified type
    $color = $colors[$type] ?? $colors['info'];
    
    // Build HTML
    $html = '<div class="alert alert-' . $type . '" role="alert" style="
        background-color: ' . $color['bg'] . ';
        border: 1px solid ' . $color['border'] . ';
        color: ' . $color['text'] . ';
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        line-height: 1.5;
        animation: slideIn 0.3s ease-out;
    ">';
    
    // Icon and message
    $html .= '<div style="display: flex; align-items: center; gap: 10px;">';
    $html .= '<span style="font-size: 20px; font-weight: bold;">' . $color['icon'] . '</span>';
    $html .= '<span>' . $message . '</span>';
    $html .= '</div>';
    
    // Close button if dismissible
    if ($dismissible) {
        $html .= '<button onclick="this.parentElement.style.display=\'none\'" style="
            background: none;
            border: none;
            color: ' . $color['text'] . ';
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            margin: 0;
            line-height: 1;
            opacity: 0.7;
        " title="Close">&times;</button>';
    }
    
    $html .= '</div>';
    
    // Add CSS animation if not already added
    static $animation_added = false;
    if (!$animation_added) {
        $html .= '<style>
            @keyframes slideIn {
                from {
                    transform: translateY(-20px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        </style>';
        $animation_added = true;
    }
    
    if ($echo) {
        echo $html;
    } else {
        return $html;
    }
}

/**
 * Success Alert
 * 
 * Displays a green success message.
 * 
 * @param string $message The success message
 * @param bool $dismissible Whether alert can be closed (default: true)
 */
function success_alert($message, $dismissible = true) {
    show_alert($message, 'success', $dismissible);
}

/**
 * Error Alert
 * 
 * Displays a red error message.
 * 
 * @param string $message The error message
 * @param bool $dismissible Whether alert can be closed (default: true)
 */
function error_alert($message, $dismissible = true) {
    show_alert($message, 'error', $dismissible);
}

/**
 * Warning Alert
 * 
 * Displays a yellow warning message.
 * 
 * @param string $message The warning message
 * @param bool $dismissible Whether alert can be closed (default: true)
 */
function warning_alert($message, $dismissible = true) {
    show_alert($message, 'warning', $dismissible);
}

/**
 * Info Alert
 * 
 * Displays a blue info message.
 * 
 * @param string $message The info message
 * @param bool $dismissible Whether alert can be closed (default: true)
 */
function info_alert($message, $dismissible = true) {
    show_alert($message, 'info', $dismissible);
}

/**
 * Display Validation Errors
 * 
 * Displays multiple validation error messages in a list.
 * 
 * @param array $errors Array of error messages
 * @param bool $dismissible Whether alert can be closed (default: true)
 */
function display_validation_errors($errors, $dismissible = true) {
    if (empty($errors)) {
        return;
    }
    
    $message = '<strong>Please correct the following errors:</strong><ul style="margin: 10px 0 0 20px; padding: 0;">';
    foreach ($errors as $error) {
        $message .= '<li>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    $message .= '</ul>';
    
    // Display as error alert (without escaping since we built the HTML safely)
    echo '<div class="alert alert-error" role="alert" style="
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
        padding: 15px 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        font-size: 14px;
        line-height: 1.5;
    ">';
    
    if ($dismissible) {
        echo '<div style="display: flex; justify-content: space-between; align-items: start;">';
        echo '<div>' . $message . '</div>';
        echo '<button onclick="this.parentElement.parentElement.style.display=\'none\'" style="
            background: none;
            border: none;
            color: #721c24;
            font-size: 20px;
            cursor: pointer;
            padding: 0;
            margin: 0;
            line-height: 1;
            opacity: 0.7;
        " title="Close">&times;</button>';
        echo '</div>';
    } else {
        echo $message;
    }
    
    echo '</div>';
}

/**
 * Alert Box with Custom Icon
 * 
 * Displays an alert with custom styling and icon.
 * 
 * @param string $title Alert title
 * @param string $message Alert message
 * @param string $icon Icon/emoji to display
 * @param string $type Alert type for color scheme
 * @param bool $dismissible Whether alert can be closed (default: true)
 */
function alert_box($title, $message, $icon = '', $type = 'info', $dismissible = true) {
    $colors = [
        'success' => ['bg' => '#d4edda', 'border' => '#c3e6cb', 'text' => '#155724'],
        'error' => ['bg' => '#f8d7da', 'border' => '#f5c6cb', 'text' => '#721c24'],
        'warning' => ['bg' => '#fff3cd', 'border' => '#ffeaa7', 'text' => '#856404'],
        'info' => ['bg' => '#d1ecf1', 'border' => '#bee5eb', 'text' => '#0c5460']
    ];
    
    $color = $colors[$type] ?? $colors['info'];
    
    echo '<div class="alert-box" style="
        background-color: ' . $color['bg'] . ';
        border: 1px solid ' . $color['border'] . ';
        color: ' . $color['text'] . ';
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        position: relative;
    ">';
    
    if ($dismissible) {
        echo '<button onclick="this.parentElement.style.display=\'none\'" style="
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            color: ' . $color['text'] . ';
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            opacity: 0.7;
        " title="Close">&times;</button>';
    }
    
    if ($icon) {
        echo '<div style="font-size: 40px; margin-bottom: 15px;">' . htmlspecialchars($icon, ENT_QUOTES, 'UTF-8') . '</div>';
    }
    
    echo '<h3 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 600;">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h3>';
    echo '<p style="margin: 0; font-size: 14px; line-height: 1.6;">' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
    echo '</div>';
}

/**
 * Confirmation Dialog Alert
 * 
 * Displays an alert that requires user confirmation.
 * Uses JavaScript confirm dialog.
 * 
 * @param string $message Confirmation message
 * @param string $confirm_url URL to redirect to on confirmation
 * @param string $cancel_url URL to redirect to on cancellation (optional)
 */
function confirmation_alert($message, $confirm_url, $cancel_url = '') {
    $message_safe = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $confirm_url_safe = htmlspecialchars($confirm_url, ENT_QUOTES, 'UTF-8');
    $cancel_url_safe = $cancel_url ? htmlspecialchars($cancel_url, ENT_QUOTES, 'UTF-8') : 'javascript:history.back()';
    
    echo '<div class="confirmation-alert" style="
        background-color: #fff3cd;
        border: 2px solid #ffc107;
        color: #856404;
        padding: 30px;
        margin: 20px 0;
        border-radius: 8px;
        text-align: center;
    ">';
    
    echo '<div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>';
    echo '<h3 style="margin: 0 0 15px 0; font-size: 20px;">Confirmation Required</h3>';
    echo '<p style="margin: 0 0 25px 0; font-size: 16px; line-height: 1.6;">' . $message_safe . '</p>';
    
    echo '<div style="display: flex; gap: 15px; justify-content: center;">';
    echo '<a href="' . $confirm_url_safe . '" style="
        background: #dc3545;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    " onclick="return confirm(\'Are you sure?\')">Confirm</a>';
    echo '<a href="' . $cancel_url_safe . '" style="
        background: #6c757d;
        color: white;
        padding: 12px 30px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 600;
        display: inline-block;
    ">Cancel</a>';
    echo '</div>';
    
    echo '</div>';
}

/**
 * Loading Alert
 * 
 * Displays a loading/processing message.
 * Useful for operations that take time.
 * 
 * @param string $message Loading message (default: 'Processing...')
 */
function loading_alert($message = 'Processing...') {
    echo '<div class="loading-alert" style="
        background-color: #d1ecf1;
        border: 1px solid #bee5eb;
        color: #0c5460;
        padding: 20px;
        margin: 20px 0;
        border-radius: 5px;
        text-align: center;
        font-size: 16px;
    ">';
    
    echo '<div style="
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #0c5460;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
        margin-right: 10px;
        vertical-align: middle;
    "></div>';
    
    echo '<span>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</span>';
    
    echo '<style>
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>';
    
    echo '</div>';
}

/**
 * USAGE EXAMPLES:
 * 
 * // Basic alerts
 * show_alert('Operation completed successfully!', 'success');
 * show_alert('An error occurred', 'error');
 * show_alert('Please review this information', 'warning');
 * show_alert('Here is some information', 'info');
 * 
 * // Shorthand functions
 * success_alert('Record saved!');
 * error_alert('Failed to save record');
 * warning_alert('This action cannot be undone');
 * info_alert('System will be down for maintenance tonight');
 * 
 * // Validation errors
 * $errors = ['Username is required', 'Email format is invalid'];
 * display_validation_errors($errors);
 * 
 * // Custom alert box
 * alert_box('Welcome!', 'Thank you for using our system', '👋', 'success');
 * 
 * // Confirmation
 * confirmation_alert(
 *     'Are you sure you want to delete this record?',
 *     'delete.php?id=123',
 *     'list.php'
 * );
 * 
 * // Loading indicator
 * loading_alert('Generating report, please wait...');
 */

/**
 * INTEGRATION WITH SESSION MESSAGES:
 * 
 * This file works seamlessly with the session message system.
 * 
 * Example:
 * 
 * // In form processor (submit.php)
 * set_session_message('Record created successfully!', 'success');
 * header("Location: list.php");
 * 
 * // In list.php
 * require_once 'includes/alerts.php';
 * $msg = get_session_message();
 * if ($msg) {
 *     show_alert($msg['message'], $msg['type']);
 * }
 * 
 * // Or even simpler, display_session_message() in header.php
 * // automatically calls show_alert() with the right parameters
 */
?>