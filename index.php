<?php
require_once __DIR__ . '/includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>LabGuard - Home</title>
	<link rel="stylesheet" href="assets/css/style.css" />
</head>
<body style="background-color: #7c3aed;">
	<?php render_navbar(); ?>
	<div class="container">
		<div class="card" style="padding:28px;">
			<div class="hero" style="grid-template-columns:1fr;">
				<div>
					<h2>Keep Labs Safe and Functional</h2>
					<p>Report issues quickly, verify efficiently, and resolve faster.</p>
					<div style="display:flex; gap:12px; flex-wrap:wrap;">
						<a class="btn" href="login_student.php">Student Login</a>
						<a class="btn secondary" href="login_admin.php">Admin Login</a>
					</div>
				</div>
			</div>
		</div>

		<div class="grid">
			<div class="card">
				<h3>For Students</h3>
				<p class="help">Submit equipment issues, damage, or missing items with an optional photo.</p>
				<a class="btn" href="login_student.php">Continue as Student</a>
			</div>
			<div class="card">
				<h3>For Admins</h3>
				<p class="help">Review, verify, and track resolution of all reported problems.</p>
				<a class="btn secondary" href="login_admin.php">Continue as Admin</a>
			</div>
		</div>
	</div>
</body>
</html>
