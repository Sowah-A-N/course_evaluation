<?php
require_once '../../config/database.php';
require_once '../../config/constants.php';
require_once '../../includes/session.php';
require_once '../../includes/csrf.php';
start_secure_session();
check_login();
if($_SESSION['role_id']!=ROLE_ADMIN){header("Location:../../login.php");exit();}
$semester_id=intval($_GET['id']??0);
$page_title='Edit Semester';
$errors=[];
$query="SELECT * FROM semesters WHERE semester_id=?";
$stmt=mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"i",$semester_id);
mysqli_stmt_execute($stmt);
$semester=mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
if(!$semester){$_SESSION['flash_message']='Semester not found.';header("Location:list.php");exit();}
if($_SERVER['REQUEST_METHOD']=='POST'){
if(!validate_csrf_token())$errors[]='Invalid token.';
$semester_name=trim($_POST['semester_name']??'');
$semester_value=intval($_POST['semester_value']??0);
if(empty($semester_name))$errors[]='Semester name required.';
if($semester_value==0)$errors[]='Semester value required.';
if(empty($errors)){
$query_check="SELECT semester_id FROM semesters WHERE semester_value=? AND semester_id!=?";
$stmt_check=mysqli_prepare($conn,$query_check);
mysqli_stmt_bind_param($stmt_check,"ii",$semester_value,$semester_id);
mysqli_stmt_execute($stmt_check);
if(mysqli_stmt_get_result($stmt_check)->num_rows>0)$errors[]='Semester value exists.';
mysqli_stmt_close($stmt_check);
}
if(empty($errors)){
$query="UPDATE semesters SET semester_name=?,semester_value=? WHERE semester_id=?";
$stmt=mysqli_prepare($conn,$query);
mysqli_stmt_bind_param($stmt,"sii",$semester_name,$semester_value,$semester_id);
if(mysqli_stmt_execute($stmt)){
$_SESSION['flash_message']='Semester updated!';
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
<div class="page-header"><h1>Edit Semester</h1></div>
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
<label class="form-label required">Semester Name</label>
<input type="text" name="semester_name" class="form-input" value="<?php echo htmlspecialchars($semester['semester_name']);?>" required>
</div>
<div class="form-group">
<label class="form-label required">Semester Value</label>
<input type="number" name="semester_value" class="form-input" value="<?php echo $semester['semester_value'];?>" min="1" required>
</div>
<button type="submit" class="btn btn-primary">Update Semester</button>
<a href="list.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
<?php require_once '../../includes/footer.php';?>
