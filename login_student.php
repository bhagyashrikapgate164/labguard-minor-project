<?php
require_once __DIR__ . '/includes/auth.php';
$conn = get_mysqli_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;
	$stmt = $conn->prepare('SELECT id, full_name FROM student WHERE id = ?');
	$stmt->bind_param('i', $student_id);
	$stmt->execute();
	$stmt->bind_result($id, $full_name);
	$found = $stmt->fetch();
	$stmt->close();
	if ($found) {
		$_SESSION['student'] = [ 'id' => $id, 'full_name' => $full_name ];
		header('Location: student_dashboard.php');
		exit;
	} else {
		$error = 'Invalid selection';
	}
}

$student = [];
$res = $conn->query('SELECT id, full_name,username,email FROM student ORDER BY full_name');
if ($res) { $student = $res->fetch_all(MYSQLI_ASSOC); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Student Login</title>
	<link rel="stylesheet" href="assets/css/style.css" />
</head>
<body style="background-color:#7c3aed;">
	<?php render_navbar(); ?>
	<div class="container">
		<div class="card" style="max-width:520px; margin:24px auto;">
			<h3 style="margin-top:0;">Student Login</h3>
			<?php if (!empty($error)): ?><p style="color:#b00020; margin-top:0;"><?= htmlspecialchars($error) ?></p><?php endif; ?>
			<form method="post">
				<label class="label" for="student_id">Select your name</label>
				<select name="student_id" id="student_id" required>
					<option value="">-- Choose --</option>
					<?php foreach ($student as $s): ?>
						<option value="<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['full_name']) ?></option>
					<?php endforeach; ?>
				</select>
				<div style="margin-top:12px; display:flex; gap:8px;">
					<button class="btn" type="submit">Login</button>
					<a class="btn secondary" href="index.php">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
