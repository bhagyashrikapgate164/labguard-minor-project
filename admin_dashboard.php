<?php
require_once __DIR__ . '/includes/auth.php';
ensure_admin_authenticated();
$conn = get_mysqli_connection();
$admin = current_admin();

// Summary stats
$stat_total = 0;
$stat_pending = 0;
$stat_verified = 0;
$stat_solved = 0;
if ($res = $conn->query('SELECT COUNT(*) FROM problems')) {
	$row = $res->fetch_row();
	$stat_total = (int)$row[0];
}
if ($res = $conn->query("SELECT COUNT(*) FROM problems WHERE status='Pending'")) {
	$row = $res->fetch_row();
	$stat_pending = (int)$row[0];
}
if ($res = $conn->query("SELECT COUNT(*) FROM problems WHERE status='Verified'")) {
	$row = $res->fetch_row();
	$stat_verified = (int)$row[0];
}
if ($res = $conn->query("SELECT COUNT(*) FROM problems WHERE status='Solved'")) {
	$row = $res->fetch_row();
	$stat_solved = (int)$row[0];
}

$filter = $_GET['status'] ?? '';
$params = [];
$sql = 'SELECT p.id, p.lab_name, p.equipment, p.issue_type, p.status, p.image_path, p.created_at, s.full_name AS student_name FROM problems p JOIN student s ON s.id = p.student_id';
if (in_array($filter, ['Pending', 'Verified', 'Solved'], true)) {
	$sql .= ' WHERE p.status = ?';
	$params[] = $filter;
}
$sql .= ' ORDER BY p.created_at DESC';

$reports = [];
if ($params) {
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $params[0]);
	$stmt->execute();
	$stmt->bind_result($id, $lab, $equip, $itype, $status, $img, $created, $student_name);
	while ($stmt->fetch()) {
		$reports[] = [
			'id' => $id,
			'lab_name' => $lab,
			'equipment' => $equip,
			'issue_type' => $itype,
			'status' => $status,
			'image_path' => $img,
			'created_at' => $created,
			'student_name' => $student_name,
		];
	}
	$stmt->close();
} else {
	$res = $conn->query($sql);
	if ($res) {
		$reports = $res->fetch_all(MYSQLI_ASSOC);
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="assets/css/style.css" />
</head>

<body style="background-color: #7c3aed;">
	<?php render_navbar(); ?>
	<div class="container">
		<div class="header">

			<div class="container,container-fluid" style="background:#05e649; border-radius:20px; padding-left: 25px; display: grid;
margin-left: 1px; width:1500px; margin-right: 1px;
">
				<h1 style="color: white;">Dashboard</h1>
				<p style="color: white;">Welcome back,Ass. Prof. <?= htmlspecialchars($admin['full_name']) ?> you can solve lab issues here !</p>
			</div>
		</div>

		<div class="grid">
			<div class="card">
				<div style="display:flex; align-items:center; justify-content:space-between;">
					<div>
						<div class="label">Total Reports</div>
						<div style="font-size:28px; font-weight:800;"><?= $stat_total ?></div>
					</div>
					<span class="badge verified">All</span>
				</div>
			</div>
			<div class="card">
				<div style="display:flex; align-items:center; justify-content:space-between;">
					<div>
						<div class="label">Pending</div>
						<div style="font-size:28px; font-weight:800;"><?= $stat_pending ?></div>
					</div>
					<span class="badge pending">Pending</span>
				</div>
			</div>
			<div class="card">
				<div style="display:flex; align-items:center; justify-content:space-between;">
					<div>
						<div class="label">Verified</div>
						<div style="font-size:28px; font-weight:800;"><?= $stat_verified ?></div>
					</div>
					<span class="badge verified">Verified</span>
				</div>
			</div>
			<div class="card">
				<div style="display:flex; align-items:center; justify-content:space-between;">
					<div>
						<div class="label">Solved</div>
						<div style="font-size:28px; font-weight:800;"><?= $stat_solved ?></div>
					</div>
					<span class="badge solved">Solved</span>
				</div>
			</div>
		</div>

		<!-- <div class="card">
			<form method="get" style="display:flex; gap:8px; align-items:center;">
				<label for="status">Filter</label>
				<select id="status" name="status">
					<option value="">All</option>
					<option <?= $filter === 'Pending' ? 'selected' : '' ?>>Pending</option>
					<option <?= $filter === 'Verified' ? 'selected' : '' ?>>Verified</option>
					<option <?= $filter === 'Solved' ? 'selected' : '' ?>>Solved</option>
				</select>
				<button class="btn small" type="submit">Apply</button>
			</form>
		</div> -->

		<div class="card">
			<h3>Reported Problems</h3>
			<table class="table">
				<tr>
					<th>ID</th>
					<th>Student</th>
					<th>Lab</th>
					<th>Equipment</th>
					<th>Issue</th>
					<th>Status</th>
					<th>Image</th>
					<th>Created</th>
					<th>Actions</th>
				</tr>
				<?php foreach ($reports as $r): ?>
					<tr>
						<td>#<?= (int)$r['id'] ?></td>
						<td><?= htmlspecialchars($r['student_name']) ?></td>
						<td><?= htmlspecialchars($r['lab_name']) ?></td>
						<td><?= htmlspecialchars($r['equipment']) ?></td>
						<td><?= htmlspecialchars($r['issue_type']) ?></td>
						<td><span class="badge <?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span></td>
						<td><?php if (!empty($r['image_path'])): ?><a href="<?= htmlspecialchars($r['image_path']) ?>" target="_blank">View</a><?php else: ?>-<?php endif; ?></td>
						<td><?= htmlspecialchars($r['created_at']) ?></td>
						<td>
							<?php if ($r['status'] === 'Pending'): ?>
								<form method="post" action="verify_report.php" style="display:inline;">
									<input type="hidden" name="id" value="<?= (int)$r['id'] ?>" />
									<button class="btn small" type="submit">Verify</button>
								</form>
							<?php endif; ?>
							<?php if (in_array($r['status'], ['Pending', 'Verified'], true)): ?>
								<form method="post" action="update_status.php" style="display:inline; margin-left:6px;">
									<input type="hidden" name="id" value="<?= (int)$r['id'] ?>" />
									<input type="hidden" name="status" value="Solved" />
									<button class="btn small secondary" type="submit">Mark Solved</button>
								</form>
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</body>

</html>