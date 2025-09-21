<?php
require_once __DIR__ . '/includes/auth.php';
ensure_admin_authenticated();
$conn = get_mysqli_connection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id = (int)$_POST['id'];
	$status = $_POST['status'] ?? '';
	if ($id > 0 && $status === 'Solved') {
		$stmt = $conn->prepare('UPDATE problems SET status = "Solved", solved_at = NOW() WHERE id = ?');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->close();
	}
}
header('Location: admin_dashboard.php');
exit;
