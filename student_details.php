<?php
require_once 'includes/auth.php';
$conn = get_mysqli_connection();

// Check admin login
ensure_admin_authenticated();

if (!isset($_GET['id'])) {
    die("Student ID missing!");
}

$id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT * FROM student WHERE student_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$student = $res->fetch_assoc();

if (!$student) die("Student not found!");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Details</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f2fafaff;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background-color: #eef6f0ff;
            color: white;
            border-radius: 0 40px 0 40px;
            padding: 30px 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .card h2 {
            margin-top: 0;
            font-size: 28px;
            background-color: #04c537;
            border-radius: 25px 25px 0 0;
            padding: 8px;
        }

        .card p {
            font-size: 18px;
            line-height: 1.6;
        }

        .label {
            font-weight: bold;
            color: #db36dbff;
        }

        .card span.value {
            color: #000;
            font-weight: 600;
        }

        a.back-btn {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 15px;
            background-color: #0de13bff;
            color: #f1f8f3ff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s, color 0.3s;
        }

        a.back-btn:hover {
            background-color: #04c537;
            color: white;
        }
    </style>
</head>
<body>
    <?php render_navbar(); ?>

    <div class="container">
        <a href="admin_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <div class="card">
            <h2><i class="fas fa-user-graduate"></i> Student Details :-</h2>
            <hr style="border-color: white; margin: 20px 0;">
            <p><span class="label">Full Name:</span> <span class="value"><?= htmlspecialchars($student['full_name']) ?></span></p>
            <p><span class="label">Roll No:</span> <span class="value"><?= htmlspecialchars($student['rollno']) ?></span></p>
            <p><span class="label">Branch:</span> <span class="value"><?= htmlspecialchars($student['branch']) ?></span></p>
            <p><span class="label">Semester:</span> <span class="value"><?= htmlspecialchars($student['sem']) ?></span></p>
            <p><span class="label">Section:</span> <span class="value"><?= htmlspecialchars($student['section']) ?></span></p>
            <p><span class="label">Contact No.:</span> <span class="value"><?= htmlspecialchars($student['contact']) ?></span></p>
            <p><span class="label">Email:</span> <span class="value"><?= htmlspecialchars($student['email']) ?></span></p>
        </div>
    </div>
</body>
</html>
