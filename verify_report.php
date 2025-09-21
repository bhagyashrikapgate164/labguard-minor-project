<?php
require_once __DIR__ . '/includes/auth.php';
ensure_admin_authenticated();
$conn = get_mysqli_connection();
$admin = current_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$id = (int)($_POST['id'] ?? 0);
	if ($id > 0) {
		$stmt = $conn->prepare('UPDATE problems SET status = "Verified", verified_by = ?, verified_at = NOW() WHERE id = ?');
		$stmt->bind_param('ii', $admin['id'], $id);
		$stmt->execute();
		$stmt->close();
	}
}
header('Location: admin_dashboard.php');
exit;
