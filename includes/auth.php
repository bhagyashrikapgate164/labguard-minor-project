<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

function ensure_student_authenticated(): void {
	if (empty($_SESSION['student'])) {
		header('Location: login_student.php');
		exit;
	}
}

function ensure_admin_authenticated(): void {
	if (empty($_SESSION['admin'])) {
		header('Location: login_admin.php');
		exit;
	}
}

function current_student(): ?array {
	return $_SESSION['student'] ?? null;
}

function current_admin(): ?array {
	return $_SESSION['admin'] ?? null;
}

function render_navbar(): void {
	$student = current_student();
	$admin = current_admin();  
	
	
?>



	<div class="navbar">
		<div class="navbar-inner">
			<div class="brand">
				<div style="font-size: 30px; " ><i class="fas fa-shield-alt icon-large text-primary"></i></div>
				<span style="font-size: 20px;"><i>LabGuard</i></span>
			</div>
			<div class="nav-links">
				<a href="index.php"><i class="fas fa-home"></i> Home</a>
				<a href="about.php"><i class="fas fa-info-circle"></i> About</a>
				<?php if ($student): ?>
					<a href="student_dashboard.php"> <i class="fas fa-tachometer-alt"></i> Dashboard</a>
				<?php endif; ?>
				<?php if ($admin): ?>
					<a href="admin_dashboard.php"> <i class="fas fa-tachometer-alt"></i> Dashboard</a>
				<?php endif; ?>
				<?php if ($student || $admin): ?>
					<a href="profile.php"> <i class="fas fa-user"></i> Profile</a>
					<a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
				<?php else: ?>
					<a href="login_student.php"><i class="fas fa-user-graduate"></i>  Student</a>
					<a href="login_admin.php"><i class="fas fa-user-cog"></i>  Admin</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="">
		    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
		
	</style>
	</head>
	<body>
		<!--[if lt IE 7]>
			<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
		<![endif]-->
		
		<script src="" async defer></script>
	</body>
</html>