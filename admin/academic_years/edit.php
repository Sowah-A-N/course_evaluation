<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/csrf.php';
start_secure_session();
check_login();
if($_SESSION['role_id']!=ROLE_ADMIN){header("Location:../../login.php");exit();}
$year_id=intval($_GET['id']??0);
$page_title='Edit Academic Year';
$errors=[];
$query="SELECT * FROM academic_year WHERE academic_year_id=?";
$stmt=mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$year_id);
mysqli_stmt_execute($stmt);
$year=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if(!$year){$_SESSION['flash_message']='Academic year not found.';header("Location:list.php");exit();}
if($_SERVER['REQUEST_METHOD']=='POST'){
if(!validate_csrf_token())$errors[]='Invalid token.';
$year_label=trim($_POST['year_label']??'');
$year_start=$_POST['year_start']??'';
$year_end=$_POST['year_end']??'';
if(empty($year_label))$errors[]='Year label required.';
if(empty($year_start))$errors[]='Start date required.';
if(empty($year_end))$errors[]='End date required.';
if(!empty($year_start)&&!empty($year_end)){
if(strtotime($year_end)<=strtotime($year_start))$errors[]='End date must be after start date.';
}
if(empty($errors)){
$query="UPDATE academic_year SET year_label=?,year_start=?,year_end=? WHERE academic_year_id=?";
$stmt=mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"sssi",$year_label,$year_start,$year_end,$year_id);
if(mysqli_stmt_execute($stmt)){
$_SESSION['flash_message']='Academic year updated!';
$_SESSION['flash_type']='success';
header("Location:list.php");
exit();
}else{$errors[]='Update failed.';}
mysqli_stmt_close($stmt);
}
}
require_once '../../includes/header.php';
?>
<style>
.form-container{max-width:800px;margin:0 auto;background:white;padding:30px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1)}
.form-group{margin-bottom:20px}
.form-label{display:block;font-size:14px;font-weight:500;margin-bottom:5px}
.form-label.required::after{content:' *';color:#dc3545}
.form-input{width:100%;padding:10px;border:2px solid #e0e0e0;border-radius:5px}
.btn{padding:12px 30px;border:none;border-radius:5px;font-size:14px;font-weight:500;cursor:pointer;text-decoration:none;display:inline-block;margin-right:10px}
.btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white}
.btn-secondary{background:#6c757d;color:white}
.alert-error{background:#f8d7da;padding:15px;border-radius:8px;margin-bottom:20px}
</style>
<div class="page-header"><h1>Edit Academic Year</h1></div>
<?php if(!empty($errors)): ?>
<div class="alert-error">
<strong>⚠️ Errors:</strong>
<ul style="margin:10px 0 0 20px"><?php foreach($errors as $e): ?><li><?php echo htmlspecialchars($e);?></li><?php endforeach;?></ul>
</div>
<?php endif;?>
<div class="form-container">
<form method="POST">
<?php csrf_token_input();?>
<div class="form-group">
<label class="form-label required">Year Label</label>
<input type="text" name="year_label" class="form-input" value="<?php echo htmlspecialchars($year['year_label']);?>" required>
</div>
<div class="form-group">
<label class="form-label required">Start Date</label>
<input type="date" name="year_start" class="form-input" value="<?php echo $year['year_start'];?>" required>
</div>
<div class="form-group">
<label class="form-label required">End Date</label>
<input type="date" name="year_end" class="form-input" value="<?php echo $year['year_end'];?>" required>
</div>
<button type="submit" class="btn btn-primary">Update Academic Year</button>
<a href="list.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
<?php require_once '../../includes/footer.php';?>
