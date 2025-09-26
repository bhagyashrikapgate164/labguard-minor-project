<?php
require_once __DIR__ . '/includes/auth.php';
$conn = get_mysqli_connection();

$student = current_student();
$admin   = current_admin();

if (!$student && !$admin) {
    header('Location: index.php');
    exit;
}

$success = '';
$error   = '';

// Agar student apna profile update kare
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile']) && $student) {
    $name    = trim($_POST['full_name']);
    $rollno  = trim($_POST['rollno']);
    $branch  = trim($_POST['branch']);
    $sem     = trim($_POST['sem']);
    $section = trim($_POST['section']);
    $contact = trim($_POST['contact']);
    $email   = trim($_POST['email']);

    if ($name && $rollno && $branch && $sem && $section && $contact && $email) {
        $stmt = $conn->prepare("UPDATE student 
                                SET full_name=?, rollno=?, branch=?, sem=?, section=?, contact=?, email=? 
                                WHERE student_id=?");
        $stmt->bind_param("sssssssi", $name, $rollno, $branch, $sem, $section, $contact, $email, $student['student_id']);
        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            // Updated data ko current session ke student array me merge kar dete hain
            $student = array_merge($student, [
                'full_name' => $name,
                'rollno'    => $rollno,
                'branch'    => $branch,
                'sem'       => $sem,
                'section'   => $section,
                'contact'   => $contact,
                'email'     => $email,
            ]);
        } else {
            $error = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Please fill all fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Profile</title>
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<style>
			
		#U {
			background-color: #07f33a;
			transform: scale(1.0);
			border-radius: 20px 20px 0 0;
			height: 50px;
			padding: 10px;
			margin-top: 2px;
		}
		
	
    form input[type="text"],
    form input[type="email"] {
        width: 100%;          
        padding: 10px;        
        margin-bottom: 15px;  
        border: 1px solid #ccc;
        border-radius: 20px;   
        box-sizing: border-box;
        font-size: 16px;
    }

    form button {
        padding: 10px 20px;
        background-color: #05e649;
        color: white;
        border: none;
        border-radius: 10px;   
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s;
    }

    form button:hover {
        background-color: #0448c5ff;
    }

    form label {
        font-weight: bold;
    }
</style>

</head>
<body style="background-color: #f2fafaff;">
	<?php render_navbar(); ?>
	<div class="container" style="margin-top:20px;">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="page-header" style="margin-top:21px; padding-bottom:20px;">
						<div class="container" style="background-color:#05e649; border-radius:0 40px 0 40px; padding-left: 40px; display: grid; margin:5px; line-height:8px;">
							<h1 style="color: white;"><i class="fas fa-user-edit me-3"></i> My Profile</h1>
							<p style="color: white; margin-top:0;">Manage your account information and settings</p>
						</div>
					</div>
				</div>
			</div>

			<div class="card" style="padding:20px;">
				<h2 id="P">📜 Profile Information 📜</h2>
				<hr>

				<?php if ($success): ?>
					<p style="color:green;"><?= $success ?></p>
				<?php endif; ?>
				<?php if ($error): ?>
					<p style="color:red;"><?= $error ?></p>
				<?php endif; ?>

				<table class="table">
					<tr>
						<th>Role :-</th>
						<td><b><?= $student ? 'Student' : 'Admin' ?></b></td>
					</tr>
					<tr>
						<th>Full Name :-</th>
						<td><b><?= htmlspecialchars(($student ? $student['full_name'] : $admin['full_name'])) ?></b></td>
					</tr>
					<?php if ($student): ?>
						<tr><th>Roll No :-</th><td><?= htmlspecialchars($student['rollno']) ?></td></tr>
						<tr><th>Branch :-</th><td><?= htmlspecialchars($student['branch']) ?></td></tr>
						<tr><th>Semester :-</th><td><?= htmlspecialchars($student['sem']) ?></td></tr>
						<tr><th>Section :-</th><td><?= htmlspecialchars($student['section']) ?></td></tr>
						<tr><th>Contact :-</th><td><?= htmlspecialchars($student['contact']) ?></td></tr>
						<tr><th>Email :-</th><td><?= htmlspecialchars($student['email']) ?></td></tr>
					<?php endif; ?>
				</table>

				<?php if ($student): ?>
				<hr>
				<h2 id="U"><i class="fas fa-edit" style="color: #0532e6ff;"></i> Update Profile :-</h2>
				<form method="POST">
					<label>Full Name:</label>
					<input type="text" name="full_name" value="<?= htmlspecialchars($student['full_name']) ?>"><br><br>

					<label>Roll No:</label>
					<input type="text" name="rollno" value="<?= htmlspecialchars($student['rollno']) ?>"><br><br>

					<label>Branch:</label>
					<input type="text" name="branch" value="<?= htmlspecialchars($student['branch']) ?>"><br><br>

					<label>Semester:</label>
					<input type="text" name="sem" value="<?= htmlspecialchars($student['sem']) ?>"><br><br>

					<label>Section:</label>
					<input type="text" name="section" value="<?= htmlspecialchars($student['section']) ?>"><br><br>

					<label>Contact:</label>
					<input type="text" name="contact" value="<?= htmlspecialchars($student['contact']) ?>"><br><br>

					<label>Email:</label>
					<input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>"><br><br>

					<button type="submit" name="update_profile">Update Profile</button>
				</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>
</html>
