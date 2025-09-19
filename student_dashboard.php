<?php
require_once __DIR__ . '/includes/auth.php';
ensure_student_authenticated();
$conn = get_mysqli_connection();
$student = current_student();

$labs = [];
if ($res = $conn->query('SELECT lab_name, room FROM labs ORDER BY lab_name')) { $labs = $res->fetch_all(MYSQLI_ASSOC); }
$faculty = [];
if ($res = $conn->query('SELECT full_name, email FROM faculty ORDER BY full_name')) { $faculty = $res->fetch_all(MYSQLI_ASSOC); }

$my_reports = [];
$stmt = $conn->prepare('SELECT id, lab_name, equipment, issue_type, status, image_path, created_at FROM problems WHERE student_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $student['id']);
$stmt->execute();
$stmt->bind_result($rid, $rlab, $req, $rtype, $rstatus, $rimg, $rcreated);
while ($stmt->fetch()) {
	$my_reports[] = [
		'id' => $rid,
		'lab_name' => $rlab,
		'equipment' => $req,
		'issue_type' => $rtype,
		'status' => $rstatus,
		'image_path' => $rimg,
		'created_at' => $rcreated,
	];
}
$stmt->close();

// Quick stats
$stat_my_total = 0; $stat_my_pending = 0; $stat_my_solved = 0;
if ($res = $conn->query('SELECT COUNT(*) FROM problems WHERE student_id='.(int)$student['id'])) { $row = $res->fetch_row(); $stat_my_total = (int)$row[0]; }
if ($res = $conn->query("SELECT COUNT(*) FROM problems WHERE student_id=".(int)$student['id']." AND status='Pending'")) { $row = $res->fetch_row(); $stat_my_pending = (int)$row[0]; }
if ($res = $conn->query("SELECT COUNT(*) FROM problems WHERE student_id=".(int)$student['id']." AND status='Solved'")) { $row = $res->fetch_row(); $stat_my_solved = (int)$row[0]; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Student Dashboard</title>
	<link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
	<?php render_navbar(); ?>
            <div class="container,container-fluid" style="background:#05e649; border-radius:20px; padding-left: 25px; display: grid;
margin: 25px;

">
                   <h1 style="color: white;">  Dashboard</h1>  <p style="color: white;">Welcome, <?= htmlspecialchars($student['full_name']) ?> you can report lab issues here !</p>
			</div>
							
			
	

		<div class="grid" style="display: flex; gap:15px; justify-content:center; justify-content:space-around; ">
			<div class="card" style= " background:#07f33a; width:375px; display:flex; ">
				<div style="display:flex; align-items:center; justify-content:space-between;" >
					<div style="background-color: #07f33a;">
						<div class="label" class="container " style="color: white;"><h2>My Reports</h2></div>
						<div style="font-size:30px; font-weight:900;"><?= $stat_my_total ?></div>
					</div>
					<span class="badge verified" style="position: relative; top:40px; left:140px; font-size:20px; color:white; background-color:blueviolet;"><b>Total</b></span>
				</div>
			</div>
			<div class="card" style= " background:#07f33a; width:375px; display:flex; ">

				<div style="display:flex; align-items:center; justify-content:space-between;
">
					<div>
						<div class="label" style="color: white;" ><h2>Pending</h2></div>
						<div style="font-size:30px; font-weight:900;"><?= $stat_my_pending ?></div>
					</div>
					<span class="badge pending" style="position: relative; top:40px; left:150px; font-size:20px;">Pending</span>
				</div>
			</div>
			<div class="card" style= " background:#07f33a; width:375px; display:flex; ">
				<div style="display:flex; align-items:center; justify-content:space-between;">
					<div>
						<div class="label" style="color: white;"><h2>Solved</h2></div>
						<div style="font-size:30px; font-weight:900;"><?= $stat_my_solved ?></div>
					</div>
					<span class="badge solved" style="position: relative; top:40px; left:180px; font-size:20px;">Solved</span>
				</div>
			</div>
		</div>

		<div class="grid">
			<div class="card" style="grid-column:span 6;">
				<h3>Lab Information</h3>
				<table class="table">
					<tr><th>Lab</th><th>Room</th></tr>
					<?php foreach ($labs as $l): ?>
						<tr><td><?= htmlspecialchars($l['lab_name']) ?></td><td><?= htmlspecialchars($l['room'] ?? '') ?></td></tr>
					<?php endforeach; ?>
				</table>
			</div>
			<div class="card" style="grid-column:span 6;">
				<h3>Faculty Information</h3>
				<table class="table">
					<tr><th>Name</th><th>Email</th></tr>
					<?php foreach ($faculty as $f): ?>
						<tr><td><?= htmlspecialchars($f['full_name']) ?></td><td><?= htmlspecialchars($f['email'] ?? '') ?></td></tr>
					<?php endforeach; ?>
				</table>
			</div>
		</div>

		<div class="card">
			<h3>Report a Problem</h3>
			<form method="post" action="submit_report.php" enctype="multipart/form-data">
				<div class="form-row">
					<div>
						<label class="label" for="lab_name">Lab</label>
						<input class="input" type="text" id="lab_name" name="lab_name" list="lablist" required />
						<datalist id="lablist">
							<?php foreach ($labs as $l): ?><option value="<?= htmlspecialchars($l['lab_name']) ?>"><?php endforeach; ?>
						</datalist>
					</div>
					<div>
						<label class="label" for="equipment">Equipment</label>
						<input class="input" type="text" id="equipment" name="equipment" required />
					</div>
				</div>
				<label class="label" for="issue_type">Issue Type</label>
				<select id="issue_type" name="issue_type" required>
					<option value="">-- Choose --</option>
					<option>Equipment Issue</option>
					<option>Damage</option>
					<option>Missing Item</option>
					<option>Hardware Issue</option>
					<option>Software Issue</option>
					<option>Network Issue</option>
					<option>Peripheral Issue</option>
					<option>General Issue</option>
				</select>
				<label class="label" for="description">Description</label>
				<textarea id="description" name="description" rows="3" required></textarea>
				<label class="label" for="image">Upload Image (optional, JPG/PNG)</label>
				<input class="input" type="file" id="image" name="image" accept="image/jpeg,image/png" />
				<div class="help">Max 5MB</div>
				<div style="margin-top:12px;">
					<button class="btn" type="submit">Submit Report</button>
				</div>
			</form>
		</div>

		<div class="card">
			<h3>My Reports</h3>
			<table class="table">
				<tr><th>ID</th><th>Lab</th><th>Equipment</th><th>Issue</th><th>Status</th><th>Image</th><th>Created</th></tr>
				<?php foreach ($my_reports as $r): ?>
					<tr>
						<td>#<?= (int)$r['id'] ?></td>
						<td><?= htmlspecialchars($r['lab_name']) ?></td>
						<td><?= htmlspecialchars($r['equipment']) ?></td>
						<td><?= htmlspecialchars($r['issue_type']) ?></td>
						<td>
							<span class="badge <?= strtolower($r['status']) ?>"><?= htmlspecialchars($r['status']) ?></span>
						</td>
						<td>
							<?php if (!empty($r['image_path'])): ?>
								<a href="<?= htmlspecialchars($r['image_path']) ?>" target="_blank">View</a>
							<?php else: ?>
								-
							<?php endif; ?>
						</td>
						<td><?= htmlspecialchars($r['created_at']) ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</body>
</html>
