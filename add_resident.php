<?php
session_start();
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "hms_db";

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo "<script>alert('Unauthorized access! Redirecting to login.'); window.location.href='admin_login.php';</script>";
    exit();
}

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("<script>alert('Database connection failed!');</script>");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $password = trim($_POST['password']);
    $age = $_POST['age'];
    $school = trim($_POST['school']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);

    // Server-side validation
    if (empty($first_name) || empty($last_name) || empty($password) || empty($age) || empty($school) || empty($address) || empty($email) || empty($phone_no)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^[0-9]{10,15}$/", $phone_no)) {
        echo "<script>alert('Invalid phone number! Must be 10-15 digits.'); window.history.back();</script>";
        exit();
    }

    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters long!'); window.history.back();</script>";
        exit();
    }

    // Hash password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_email = $conn->prepare("SELECT id FROM residents WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists! Please use a different email.'); window.history.back();</script>";
        exit();
    }

    // Insert new resident
    $sql = "INSERT INTO residents (first_name, last_name, password, age, school, address, email, phone_no) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissss", $first_name, $last_name, $hashed_password, $age, $school, $address, $email, $phone_no);

    if ($stmt->execute()) {
        echo "<script>alert('Resident added successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding resident.');</script>";
    }

    $stmt->close();
    $check_email->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Resident</title>
    <script src="validate_form.js"></script>
    <link rel="stylesheet" href="add.css">
</head>
<body>
    <div>
    <img src="logo.png" alt="Logo">
    </div>
    <div>
    <div class="update-container">
    <h2>Add Resident</h2>
        <div class="update-form">
            <form name="residentForm" method="POST" action="add_resident.php" onsubmit="return validateForm()">
                <label>First Name:</label>
                <input type="text" name="first_name" required autocomplete="off"><br>

                <label>Last Name:</label>
                <input type="text" name="last_name" required autocomplete="off"><br>

                <label>Password:</label>
                <input type="password" name="password" required autocomplete="off"><br>

                <label>Age:</label>
                <input type="number" name="age" required min="10" autocomplete="off"><br>

                <label>School:</label>
                <input type="text" name="school" required autocomplete="off"><br>

                <label>Address:</label>
                <input type="text" name="address" required autocomplete="off"><br>

                <label>Email:</label>
                <input type="email" name="email" required autocomplete="off"><br>

                <label>Phone No.:</label>
                <input type="text" name="phone_no" required autocomplete="off"><br>

                 <!-- Buttons fixed at the bottom -->
                <div class="button-container">
                <button type="submit">Add Resident</button>
                <button type="button" onclick="window.location.href='admin_dashboard.php'">Back</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

