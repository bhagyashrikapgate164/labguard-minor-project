<?php
require_once __DIR__ . '/includes/auth.php';
$conn = get_mysqli_connection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, full_name, password FROM student WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $full_name, $hashed_password);
            $found=$stmt->fetch();
			$stmt->close();

            if ($password === $hashed_password || password_verify($password, $hashed_password)) {
                // Login success
                $_SESSION['student_id'] = $id;
                $_SESSION['student_name'] = $full_name;
				$_SESSION['student'] = ['password' => $password, 'full_name' => $full_name];
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
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Student Login</title>
	<link rel="stylesheet" href="assets/css/style.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>

        .container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh; /* pura screen height */
    background: #f0f0f0; /* optional background */
}

.card {
    background: #fff;
    padding: 30px 25px;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 420px;
    text-align: center; 
}

#s {
    margin-bottom: 20px; 
    text-align: center;
    color: #333;
}

#b{
    display: flex;
    justify-content: center;
   
}
input[type="text"],
input[type="password"] {
    border-radius: 20px; 
    border: 1px solid #2e9402ff; 
    padding: 10px; 
    outline: none;  
    font-size: 15px;
    width: 80%; 
    box-sizing: border-box;
}
 
       
       
    
    </style>
	
</head>

<body style="background-color: #f2eefaff;">
	<?php render_navbar(); ?>
	<div class="container">
		<div class="card" style="max-width:520px; margin:24px auto; text-align:center;">
			
                <h1 id="s" ><i class="fas fa-user-graduate"></i> Student Login</h1>
            
			<?php if (!empty($error)): ?><p style="color:#b00020; margin-top:0;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="post">
        <label class="label" for="username"><h3>Username</h3></label>
        <input type="text" name="username" id="username" required>

        <label class="label" for="password" style="margin-top:12px;"><h3>Password</h3></label>
        <input type="password" name="password" id="password" required>

        <div id="b" style="margin-top:12px; display:flex; gap:8px; ">
            <button class="btn" type="submit" >Login</button>
            <a class="btn secondary" href="index.php">Cancel</a>
        </div>
    </form>
   </div>
</div>

	</body>
	</html>
			
			   