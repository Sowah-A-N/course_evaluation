<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
start_secure_session();
check_login();
if($_SESSION['role_id']!=ROLE_ADMIN){header("Location:../../login.php");exit();}
$page_title='System Settings';
require_once '../../includes/header.php';
?>
<style>
.settings-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(350px,1fr));gap:25px;margin-bottom:30px}
.setting-card{background:white;border-radius:12px;padding:30px;box-shadow:0 2px 8px rgba(0,0,0,0.1);transition:all 0.3s;border-left:5px solid #667eea}
.setting-card:hover{transform:translateY(-5px);box-shadow:0 6px 20px rgba(0,0,0,0.15)}
.setting-icon{font-size:48px;margin-bottom:15px}
.setting-title{font-size:20px;font-weight:700;color:#333;margin-bottom:10px}
.setting-desc{font-size:14px;color:#666;margin-bottom:20px;line-height:1.6}
.btn{display:block;padding:12px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;text-decoration:none;text-align:center;border-radius:8px;font-weight:600;transition:all 0.3s}
.btn:hover{transform:scale(1.05);box-shadow:0 4px 12px rgba(102,126,234,0.4)}
.info-box{background:#d1ecf1;border:1px solid #bee5eb;color:#0c5460;padding:20px;border-radius:8px;margin-bottom:30px}
</style>
<div class="page-header">
<h1>⚙️ System Settings</h1>
<p>Configure system parameters and application settings</p>
</div>
<div class="info-box">
<strong>ℹ️ Note:</strong> Some settings require server access or database changes. Always backup before making configuration changes.
</div>
<div class="settings-grid">
<div class="setting-card">
<div class="setting-icon">🎓</div>
<div class="setting-title">Institution Information</div>
<div class="setting-desc">Configure institution name, logo, contact details, and branding</div>
<a href="institution.php" class="btn">Manage Institution Info</a>
</div>
<div class="setting-card">
<div class="setting-icon">🔒</div>
<div class="setting-title">Security Settings</div>
<div class="setting-desc">Configure session timeout, password policies, and security parameters</div>
<a href="security.php" class="btn">Manage Security</a>
</div>
<div class="setting-card">
<div class="setting-icon">📧</div>
<div class="setting-title">Email Configuration</div>
<div class="setting-desc">Set up email server, templates, and notification preferences</div>
<a href="email.php" class="btn">Configure Email</a>
</div>
<div class="setting-card">
<div class="setting-icon">🎨</div>
<div class="setting-title">Appearance & Theme</div>
<div class="setting-desc">Customize colors, fonts, logo, and user interface elements</div>
<a href="appearance.php" class="btn">Customize Appearance</a>
</div>
<div class="setting-card">
<div class="setting-icon">📊</div>
<div class="setting-title">Evaluation Settings</div>
<div class="setting-desc">Configure evaluation periods, anonymity threshold, and rating scales</div>
<a href="evaluation.php" class="btn">Evaluation Settings</a>
</div>
<div class="setting-card">
<div class="setting-icon">🔧</div>
<div class="setting-title">System Maintenance</div>
<div class="setting-desc">Enable maintenance mode, backup database, view system logs</div>
<a href="maintenance.php" class="btn">Maintenance Tools</a>
</div>
</div>
<div style="background:white;padding:30px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1)">
<h2 style="margin:0 0 20px 0;color:#667eea">Current Configuration</h2>
<table style="width:100%;border-collapse:collapse">
<tr style="border-bottom:1px solid #f0f0f0">
<td style="padding:15px;font-weight:600">Application Name</td>
<td style="padding:15px"><?php echo APP_NAME;?></td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0">
<td style="padding:15px;font-weight:600">Institution</td>
<td style="padding:15px"><?php echo INSTITUTION_NAME;?></td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0">
<td style="padding:15px;font-weight:600">Session Timeout</td>
<td style="padding:15px"><?php echo SESSION_TIMEOUT/60;?> minutes</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0">
<td style="padding:15px;font-weight:600">Min Password Length</td>
<td style="padding:15px"><?php echo PASSWORD_MIN_LENGTH;?> characters</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0">
<td style="padding:15px;font-weight:600">Anonymity Threshold</td>
<td style="padding:15px"><?php echo MIN_RESPONSE_COUNT;?> responses</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0">
<td style="padding:15px;font-weight:600">Maintenance Mode</td>
<td style="padding:15px"><?php echo MAINTENANCE_MODE?'<span style="color:#dc3545;font-weight:600">ENABLED</span>':'<span style="color:#28a745">Disabled</span>';?></td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0">
<td style="padding:15px;font-weight:600">Database</td>
<td style="padding:15px"><?php echo DB_NAME;?></td>
</tr>
<tr>
<td style="padding:15px;font-weight:600">PHP Version</td>
<td style="padding:15px"><?php echo phpversion();?></td>
</tr>
</table>
</div>
<?php require_once '../../includes/footer.php';?>
</div>
<?php if($success): ?>
<div class="alert alert-success">
<strong>✓ Settings Noted!</strong><br>
To apply changes, edit <code>config/constants.php</code> with the values you specified.
</div>
<?php endif;?>
<?php if(!empty($errors)): ?>
<div class="alert alert-error">
<strong>⚠️ Errors:</strong>
<ul style="margin:10px 0 0 20px;padding:0">
<?php foreach($errors as $error): ?>
<li><?php echo htmlspecialchars($error);?></li>
<?php endforeach;?>
</ul>
</div>
<?php endif;?>
<form method="POST">
<?php csrf_token_input();?>
<div class="settings-section">
<h2>📊 Evaluation Settings</h2>
<div class="form-group">
<label class="form-label">Minimum Response Count (Anonymity Protection)</label>
<div class="form-description">
Minimum number of responses required before showing evaluation results. Protects individual responses from being identified.
</div>
<div class="current-value">Current: <?php echo $current_min_response;?></div>
<input type="number" name="min_response_count" class="form-input" value="<?php echo $current_min_response;?>" min="1" max="20">
<div style="margin-top:10px;font-size:13px;color:#666">
<strong>Recommended:</strong> 5 responses<br>
<strong>Edit in:</strong> <code>config/constants.php</code> → <code>MIN_RESPONSE_COUNT</code>
</div>
</div>
</div>
<div class="settings-section">
<h2>🔒 Security Settings</h2>
<div class="form-group">
<label class="form-label">Session Timeout (seconds)</label>
<div class="form-description">
How long a user can be inactive before being automatically logged out. Affects all user sessions system-wide.
</div>
<div class="current-value">Current: <?php echo $current_session_timeout;?>s (<?php echo round($current_session_timeout/60);?> min)</div>
<input type="number" name="session_timeout" class="form-input" value="<?php echo $current_session_timeout;?>" min="300" max="7200" step="300">
<div style="margin-top:10px;font-size:13px;color:#666">
<strong>Recommended:</strong> 1800 seconds (30 minutes)<br>
<strong>Edit in:</strong> <code>config/constants.php</code> → <code>SESSION_TIMEOUT</code>
</div>
</div>
</div>
<div class="settings-section">
<h2>🔧 System Maintenance</h2>
<div class="form-group">
<label class="form-label">
<input type="checkbox" name="maintenance_mode" class="form-checkbox" <?php echo $current_maintenance?'checked':'';?>>
Enable Maintenance Mode
</label>
<div class="form-description">
When enabled, only administrators can access the system. All other users will see a maintenance message.
</div>
<div class="current-value">Current: <?php echo $current_maintenance?'ON':'OFF';?></div>
<div style="margin-top:10px;font-size:13px;color:#666">
<strong>Edit in:</strong> <code>config/constants.php</code> → <code>MAINTENANCE_MODE</code> (true/false)
</div>
</div>
</div>
<div class="settings-section">
<h2>📝 Instructions</h2>
<div class="info-box">
<strong>How to Apply These Settings:</strong>
<ol style="margin:10px 0 0 20px;line-height:1.8">
<li>Note the values you want to change in this form</li>
<li>Open <code>config/constants.php</code> on your server</li>
<li>Find the constant you want to change (e.g., <code>MIN_RESPONSE_COUNT</code>)</li>
<li>Update its value: <code>define('MIN_RESPONSE_COUNT', 5);</code></li>
<li>Save the file</li>
<li>Changes take effect immediately (no restart needed)</li>
</ol>
</div>
</div>
<button type="submit" class="btn btn-primary">Save Settings Reference</button>
<a href="../index.php" class="btn btn-secondary">Back to Dashboard</a>
</form>
</div>
<?php require_once '../../includes/footer.php';?>
