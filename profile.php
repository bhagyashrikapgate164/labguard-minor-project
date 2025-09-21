<?php
require_once __DIR__ . '/includes/auth.php';
$student = current_student();
$admin = current_admin();
if (!$student && !$admin) {
	header('Location: index.php');
	exit;
}

// $conn = mysqli_connect("localhost", "root", "", "labguard");
// $query = "SELECT  email FROM student WHERE email = '$email'";
// $student = mysqli_fetch_assoc(mysqli_query($conn, $query));




?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Profile</title>
	<link rel="stylesheet" href="assets/css/style.css" />
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body style="background-color: #f2fafaff;">
	<?php render_navbar(); ?>
	<div class="container" style="margin-top:20px; ">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="page-header" style="margin-top:21px; padding-bottom:20px;">
						<div class="container" style="background-color:#05e649; border-radius:0 40px 0 40px; padding-left: 40px; display: grid; margin:5px; line-height:8px;  ">
							<h1 style="color: white;"><i class="fas fa-user-edit me-3"></i> My Profile</h1>
							<p style="color: white; margin-top:0;">Manage your account information and settings</p>
						</div>
					</div>
				</div>
			</div>



			<div class="card">
				<h2>📜 Profile Information 📜</h2>
				<hr>
				<table class="table">
					<tr>
						<th><b>Role :-</b></th>
						<td><b><?= $student ? 'Student' : 'Admin' ?></b></td>
					</tr>
					<tr>
						<th><b>Full Name :-</b></th>
						<td><b><?= htmlspecialchars(($student ? $student['full_name'] : $admin['full_name'])) ?></b></td>
					</tr>
					<tr>
						<!-- <th><b>Username :-</b></th>
    <td><b>bhagyashri</b></td>
</tr>
<tr><th><b>Email :-</b></th><td><b>bhagyashrikapgate@gmail.com</b></td></tr>
<tr><th><b>Role Number :-</b></th><td><b>A36</b></td></tr> -->







</body>

</html>