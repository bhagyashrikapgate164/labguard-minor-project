 <?php
require_once __DIR__ . '/includes/auth.php';
 $conn = get_mysqli_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = trim($_POST['full_name'] ?? '');
	$pass = trim($_POST['password'] ?? '');
	$stmt = $conn->prepare('SELECT id, full_name FROM admins WHERE full_name = ? AND password = ?');
	$stmt->bind_param('ss', $name, $pass);
	$stmt->execute();
	$stmt->bind_result($id, $full_name);
	$found = $stmt->fetch();
	$stmt->close();
	if ($found) {
		$_SESSION['admin'] = [ 'id' => $id, 'full_name' => $full_name ];
		header('Location: admin_dashboard.php');
		exit;
	} else {
		$error = 'Invalid credentials';
	}
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Admin Login</title> 
	<link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body style="background-color: #f2fcf8ff;">
	 <?php render_navbar(); ?> 
	<div class="container">
		<div class="card" style="max-width:520px; margin:24px auto;">
			<h3 style="margin-top:0;">Admin Login</h3>
			<?php if (!empty($error)): ?><p style="color:#b00020; margin-top:0;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
			<form method="post">
				<label class="label" for="full_name">Full name</label>
				<input class="input" type="text" id="full_name" name="full_name" required />
				<label class="label" for="password">Password</label>
				<input class="input" type="password" id="password" name="password" required />
				<div style="margin-top:12px; display:flex; gap:8px;">
					<button class="btn" type="submit">Login</button>
					<a class="btn secondary" href="index.php">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html> 

