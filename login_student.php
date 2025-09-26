<?php
session_start();
require_once __DIR__ . '/includes/auth.php';
$conn = get_mysqli_connection();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // LOGIN
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if ($username && $password) {
            $stmt = $conn->prepare("SELECT student_id, full_name, password FROM student WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {
                $stmt->bind_result($student_id, $full_name, $hashed_password);
                $stmt->fetch();
                $stmt->close();

                if ($password === $hashed_password || password_verify($password, $hashed_password)) {
                    $_SESSION['student'] = [
                        'student_id' => $student_id,
                        'full_name'  => $full_name
                    ];
                    header("Location: student_dashboard.php");
                    exit;
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "Student not found!";
            }
        } else {
            $error = "Please enter both username and password!";
        }
    }

    // SIGNUP
    if (isset($_POST['signup'])) {
        $name     = trim($_POST['name']);
        $username = trim($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $rollno   = trim($_POST['rollno']);
        $branch   = trim($_POST['branch']);
        $sem      = trim($_POST['sem']);
        $section  = trim($_POST['section']);
        $contact  = trim($_POST['contact']);
        $email  = trim($_POST['email']);

        if ($name && $username && $rollno && $branch && $sem && $section && $contact &&$email) {
            $stmt = $conn->prepare("INSERT INTO student (full_name, username, password, rollno, branch, sem, section, contact,email) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)");
            $stmt->bind_param("sssssssss", $name, $username, $password, $rollno, $branch, $sem, $section, $contact, $email);
            if ($stmt->execute()) {
                $success = "Account created successfully! Please login.";
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = "Please fill all required fields!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Student Portal</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<style>


     .container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    background: #f0f0f0; 
}

.card {
    width: 50%;
    min-width: 320px;
    max-width: 480px;
    background: #fff;
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    text-align: center;
}
h1 {
    font-size: 26px;
    margin-bottom: 15px;
    color: #333;
}
.tabs {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 15px;
}
.tabs button {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 40px;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}
.tabs button.login {
    background: #3498db;
    color: #fff;
}
.tabs button.login:hover {
    background: #1d6fc1;
}
.tabs button.signup {
    background: #ff4da6;
    color: #fff;
}
.tabs button.signup:hover {
    background: #cc0077;
}
form { display: none; text-align: left; }
form.active { display: block; }
input, select {
    width: 100%;
    padding: 10px;
    margin: 6px 0;
    border-radius: 40px;
    border: 1px solid #2e9402ff;
    box-sizing: border-box;
    font-size: 14px;
}

/* Submit & Cancel buttons container */
.button-group {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 12px;
}

button.submit-btn {
    border: none;
    border-radius: 10px;
    cursor: pointer;
    background: #05db13ff;
    color: #fff;
    font-size: 15px;
    transition: 0.3s;
    height: 6vh;
   
}
button.submit-btn:hover { background: #0b5dd7ff; }

/* Login & Signup width */
#loginForm button.submit-btn { width: 80%; }
#signupForm button.submit-btn { width: 50%; }

/* Cancel button style */
button.cancel-btn {
   
    border-radius: 10px;
    background: #f7f5f5ff;
    color: #0c0b0bff;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
    border: 1px solid #41fae8ff;
}
button.cancel-btn:hover { background: #f018c1ff; }

p.msg { color: #b00020; text-align:center; }
p.success { color: green; text-align:center; }





</style>
<script>
function showForm(formId, btn) {
    document.getElementById("loginForm").classList.remove("active");
    document.getElementById("signupForm").classList.remove("active");
    document.getElementById(formId).classList.add("active");

    document.querySelectorAll(".tabs button").forEach(b=>b.classList.remove("active"));
    btn.classList.add("active");
}
</script>
</head>
<body style="background-color: #f2fcf8ff;">
     <?php  render_navbar(); ?> 
 <div class="container">

<div class="card">
    <h1><i class="fas fa-user-graduate"></i> Student Portal</h1>

    <?php if (!empty($error)): ?><p class="msg"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if (!empty($success)): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <div class="tabs">
        <button class="login active" onclick="showForm('loginForm', this)">Sign In</button>
        <button class="signup" onclick="showForm('signupForm', this)">Sign Up</button>
    </div>

    <!-- Login Form -->
    <form id="loginForm" class="active" method="post">
        <label class="label" for="full_name"><h3 style="text-align: center;">username</h3></label>
        <input type="text" name="username" placeholder="" required>
        <label class="label" for="full_name"><h3 style="text-align: center;">password</h3></label>
        <input type="password" name="password" placeholder="" required>
        <div class="button-group">
            <button class="submit-btn" type="submit" name="login" style="width: 5vw; height:7vh;">Login</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">Cancel</button>
        </div>
    </form>

    <!-- Signup Form -->
    <form id="signupForm" method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="username" placeholder="Username" required>
        <input type="text" name="rollno" placeholder="Roll No" required>
        <input type="text" name="email" placeholder="Email" required>

        <select name="branch" required>
            <option value="">-- Select Branch --</option>
            <option value="CSE">CSE</option>
            <option value="IT">IT</option>
            <option value="ECE">ECE</option>
            <option value="EEE">EEE</option>
            <option value="ME">ME</option>
            <option value="CIVIL">CIVIL</option>
        </select>

        <select name="sem" required>
            <option value="">-- Select Semester --</option>
            <option value="1st">1st Semester</option>
            <option value="2nd">2nd Semester</option>
            <option value="3rd">3rd Semester</option>
            <option value="4th">4th Semester</option>
            <option value="5th">5th Semester</option>
            <option value="6th">6th Semester</option>
            <option value="7th">7th Semester</option>
            <option value="8th">8th Semester</option>
        </select>

        <select name="section" required>
            <option value="">-- Select Section --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="C">D</option>
            <option value="C">E</option>
            <option value="C">F</option>
            <option value="C">G</option>
            <option value="C">H</option>
        </select>

        <input type="text" name="contact" placeholder="Contact Number" required>
        <input type="password" name="password" placeholder="Password" required>

        <div class="button-group">
            <button class="submit-btn" type="submit" name="signup" >Register</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">Cancel</button>
        </div>
    </form>
</div>

</body>
</html>
