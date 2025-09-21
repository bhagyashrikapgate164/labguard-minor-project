 <?php
    $host = "localhost";
    $db   = "labguard";   // tumhara database
    $user = "root";       // database username
    $pass = "";           // database password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
    ?>

<?php


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // safe cast to int

    $stmt = $pdo->prepare("DELETE FROM problems WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect back to reports page
    header("Location: student_dashboard.php");
    exit;
} else {
    die("ID not provided.");
}
?>