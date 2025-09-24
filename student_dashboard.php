<?php
require_once __DIR__ . '/includes/auth.php';
ensure_student_authenticated();
$conn = get_mysqli_connection();
$student = current_student();


$labs = [];
if ($res = $conn->query('SELECT lab_name, room FROM labs ORDER BY lab_name')) {
	$labs = $res->fetch_all(MYSQLI_ASSOC);
}
$faculty = [];
if ($res = $conn->query('SELECT full_name, email FROM faculty ORDER BY full_name')) {
	$faculty = $res->fetch_all(MYSQLI_ASSOC);
}

$my_reports = [];
$stmt = $conn->prepare('SELECT student_id, room, equipment, issue_type,description, status, image_path, created_at FROM problems WHERE student_id = ? ORDER BY created_at DESC');
$stmt->bind_param('i', $student['student_id']);
$stmt->execute();
$stmt->bind_result($rid, $rroom, $req, $rtype, $description, $rstatus, $rimg, $rcreated);

$sr = 1;
while ($stmt->fetch()) {
	$my_reports[] = [
		'sr No.' => $sr,
		'id' => $rid,
		'room' => $rroom,
		'equipment' => $req,
		'issue_type' => $rtype,
		'description' => $description,
		'status' => $rstatus,
		'image_path' => $rimg,
		'created_at' => $rcreated,
	];
	$sr++;
}
$stmt->close();


// Quick stats
$stat_my_total = 0;
$stat_my_pending = 0;
$stat_my_solved = 0;
if ($res = $conn->query('SELECT COUNT(*) FROM problems WHERE student_id=' . (int)$student['student_id'])) {
	$row = $res->fetch_row();
	$stat_my_total = (int)$row[0];
}
if ($res = $conn->query("SELECT COUNT(*) FROM problems WHERE student_id=" . (int)$student['student_id'] . " AND status='Pending'")) {
	$row = $res->fetch_row();
	$stat_my_pending = (int)$row[0];
}
if ($res = $conn->query("SELECT COUNT(*) FROM problems WHERE student_id=" . (int)$student['student_id'] . " AND status='Solved'")) {
	$row = $res->fetch_row();
	$stat_my_solved = (int)$row[0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Student Dashboard</title>
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<style>
		#P {
			background-color: #07f33a;
			transform: scale(1.0);
			border-radius: 20px 20px 0 0;
			height: 50px;
			padding: 10px;
			margin-top: 2px;
		}

		#M {
			background-color: #07f33a;
			transform: scale(1.0);
			border-radius: 20px 20px 0 0;
			height: 50px;
			padding: 10px;
			margin-top: 2px;
		}
	</style>


</head>

<body style="background-color: #ebf5f3ff;">
	<?php render_navbar(); ?>
	<div class="container,container-fluid" style="background: #1ccf63ff; border-radius:0 0 30px 30px; padding-left: 25px; display: grid;
                  margin: 20px 10px 20px 10px; height:115px;

">
		<h1 style="color: white; margin-bottom:1px;"><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
		<p style="color:  white; margin-top:0px;">Welcome, <?= htmlspecialchars($student['full_name']) ?> you can report lab issues here !</p>




	</div>





	<div class="grid" style="display: flex; gap:15px; justify-content:center; justify-content:space-around; ">
		<div class="card" style=" background:#07f33a; width:375px; display:flex;  border-radius:0 50px 0 50px;">
			<div style="display:flex; align-items:center; justify-content:space-between;">
				<div style="background-color: #07f33a;">
					<div class="label" class="container " style="color: white; ">
						<h2>My Reports</h2>
					</div>
					<div style="font-size:30px; font-weight:900;"><?= $stat_my_total ?></div>
				</div>
				<span class="badge verified" style="position: relative; top:40px; left:140px; font-size:20px; color:white; background-color:blueviolet;"><b>Total</b></span>
			</div>
		</div>
		<div class="card" style=" background: #07f33a; width:375px; display:flex;  border-radius:0 50px 0 50px;">

			<div style="display:flex; align-items:center; justify-content:space-between;
">
				<div>
					<div class="label" style="color: white;">
						<h2>Pending</h2>
					</div>
					<div style="font-size:30px; font-weight:900;"><?= $stat_my_pending ?></div>
				</div>
				<span class="badge pending" style="position: relative; top:40px; left:150px; font-size:20px;">Pending</span>
			</div>
		</div>
		<div class="card" style=" background:#07f33a; width:375px; display:flex;  border-radius:0 50px 0 50px; ">
			<div style="display:flex; align-items:center; justify-content:space-between;">
				<div>
					<div class="label" style="color: white;">
						<h2>Solved</h2>
					</div>
					<div style="font-size:30px; font-weight:900;"><?= $stat_my_solved ?></div>
				</div>
				<span class="badge solved" style="position: relative; top:40px; left:180px; font-size:20px;">Solved</span>
			</div>
		</div>
	</div>

	<div class="grid">
		<div class="card" style="grid-column:span 6; ">
			<h3><i class="fas fa-shield-alt icon-large text-primary"></i> Lab Information :-</h3>
			<hr>
			<table class="table">
				<tr>
					<th>Lab Name</th>
					<th>Room No.</th>
				</tr>
				<?php foreach ($labs as $l): ?>
					<tr>
						<td><?= htmlspecialchars($l['lab_name']) ?></td>
						<td><?= htmlspecialchars($l['room'] ?? '') ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<div class="card" style="grid-column:span 6;">
			<h3><i class="fas fa-info-circle"></i> Faculty Information :-</h3>
			<hr>
			<table class="table">
				<tr>
					<th>Faculty Name</th>
					<th>Email</th>
				</tr>
				<?php foreach ($faculty as $f): ?>
					<tr>
						<td><?= htmlspecialchars($f['full_name']) ?></td>
						<td><?= htmlspecialchars($f['email'] ?? '') ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>

	<div class="card">
		<h3 id="P"><i class="fas fa-exclamation-triangle"></i> Report a Problem :-</h3>
		<hr>
		<form method="post" action="submit_report.php" enctype="multipart/form-data">
			<div class="form-row">
				<div>
					<label class="label" for="room">Room No.</label>
					<input class="input" type="text" id="room" name="room" list="lablist" required />
					<datalist id="lablist">
						<?php foreach ($labs as $l): ?><option value="<?= htmlspecialchars($l['room']) ?>"><?php endforeach; ?>
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
		<h3 id="M"><i class="fas fa-file-alt"></i> My Reports :-</h3>
		<hr>
		<table class="table">
			<tr>
				<th>Sr No.</th>
				<th>Room no.</th>
				<th>Equipment</th>
				<th>Issue Type</th>
				<th>Description</th>
				<th>Status</th>
				<th>Image</th>
				<th>Created</th>
			</tr>
			<?php foreach ($my_reports as $r): ?>
				<tr>
					<td><?= (int)$r['sr No.'] ?></td>
					<td><?= htmlspecialchars($r['room']) ?></td>
					<td><?= htmlspecialchars($r['equipment']) ?></td>
					<td><?= htmlspecialchars($r['issue_type']) ?></td>
					<td><?= htmlspecialchars($r['description']) ?></td>
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