<?php
session_start();
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "hms_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("<script>alert('Connection failed: " . $conn->connect_error . "');</script>");
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the admin table
    $sql = "SELECT * FROM admin WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['first_name'];
            echo "<script>alert('Login successful! Redirecting...'); window.location.href='admin_dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('Admin not found.');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div>
    <img src="logo.png" alt="Logo">
    </div>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="post" autocomplete="off">
            Email: <input type="email" name="email" required autocomplete="off"><br>
            Password: <input type="password" name="password" required autocomplete="new-password"><br>
            <button type="submit">Login</button>
        </form>
        
        <div class="navigation-buttons">
            <button onclick="window.location.href='resident_login.php';">Go to Resident Login</button>
        </div>
    </div>
</body>
</html>
