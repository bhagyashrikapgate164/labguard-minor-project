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
				<div class="logo"></div>
				<span>LabGuard</span>
			</div>
			<div class="nav-links">
				<a href="index.php">Home</a>
				<a href="about.php">About</a>
				<?php if ($student): ?>
					<a href="student_dashboard.php">Dashboard</a>
				<?php endif; ?>
				<?php if ($admin): ?>
					<a href="admin_dashboard.php">Dashboard</a>
				<?php endif; ?>
				<?php if ($student || $admin): ?>
					<a href="profile.php">Profile</a>
					<a href="logout.php">Logout</a>
				<?php else: ?>
					<a href="login_student.php">Student</a>
					<a href="login_admin.php">Admin</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}
?>
