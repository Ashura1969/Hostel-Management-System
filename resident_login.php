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

    // Query the residents table
    $sql = "SELECT * FROM residents WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['resident_id'] = $row['id'];
            $_SESSION['resident_name'] = $row['first_name'];
            echo "<script>alert('Login successful! Redirecting...'); window.location.href='resident_dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resident Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div>
    <img src="logo.png" alt="Logo">
    </div>
    <div class="login-container">
        <h2>Resident Login</h2>
        <form method="post" autocomplete="off">
            Email: <input type="email" name="email" required autocomplete="off"><br>
            Password: <input type="password" name="password" required autocomplete="new-password"><br>
            <button type="submit">Login</button>
        </form>
        
        <div class="navigation-buttons">
            <button onclick="window.location.href='admin_login.php';">Go to Admin Login</button>
        </div>
    </div>
</body>
</html>
