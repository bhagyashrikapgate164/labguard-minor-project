<?php
require_once __DIR__ . '/includes/auth.php';
ensure_student_authenticated();
$conn = get_mysqli_connection();
$student = current_student();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: student_dashboard.php');
	exit;
}

$lab_name = trim($_POST['lab_name'] ?? '');
$equipment = trim($_POST['equipment'] ?? '');
$issue_type = trim($_POST['issue_type'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($lab_name === '' || $equipment === '' || $issue_type === '' || $description === '') {
	header('Location: student_dashboard.php');
	exit;
}

$image_path = null;
if (!empty($_FILES['image']['name'])) {
	$err = $_FILES['image']['error'];
	if ($err === UPLOAD_ERR_OK) {
		$tmp = $_FILES['image']['tmp_name'];
		$size = (int)$_FILES['image']['size'];
		if ($size > MAX_UPLOAD_BYTES) {
			goto done;
		}
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $tmp);
		finfo_close($finfo);
		$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
		if (!isset($allowed[$mime])) {
			goto done;
		}
		$ext = $allowed[$mime];
		if (!is_dir(__DIR__ . '/uploads')) {
			mkdir(__DIR__ . '/uploads', 0777, true);
		}
		$filename = 'report_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
		$dest = __DIR__ . '/uploads/' . $filename;
		if (move_uploaded_file($tmp, $dest)) {
			$image_path = 'uploads/' . $filename;
		}
	}
}

done:
$stmt = $conn->prepare('INSERT INTO problems (student_id, lab_name, equipment, issue_type, description, image_path) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->bind_param('isssss', $student['id'], $lab_name, $equipment, $issue_type, $description, $image_path);
$stmt->execute();
$stmt->close();

header('Location: student_dashboard.php');
exit;
