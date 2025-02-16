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

$admin_id = $_SESSION['admin_id'];

// Fetch current admin details
$sql_admin = "SELECT first_name, last_name, email, phone_no, password FROM admin WHERE id=?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("i", $admin_id);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();
$admin = $result_admin->fetch_assoc();
$stmt_admin->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);

    // Server-side validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_no) || empty($current_password)) {
        echo "<script>alert('All fields except new password are required!'); window.history.back();</script>";
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

    // Verify current password
    if (!password_verify($current_password, $admin['password'])) {
        echo "<script>alert('Incorrect current password!'); window.history.back();</script>";
        exit();
    }

    // Update admin details
    if (!empty($new_password)) {
        if (strlen($new_password) < 6) {
            echo "<script>alert('New password must be at least 6 characters long!'); window.history.back();</script>";
            exit();
        }
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql_update = "UPDATE admin SET first_name=?, last_name=?, email=?, phone_no=?, password=? WHERE id=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("sssssi", $first_name, $last_name, $email, $phone_no, $hashed_password, $admin_id);
    } else {
        $sql_update = "UPDATE admin SET first_name=?, last_name=?, email=?, phone_no=? WHERE id=?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone_no, $admin_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Admin details updated successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating details.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Admin</title>
    <script src="validate_admin.js"></script>
    <link rel="stylesheet" href="update.css">
</head>
<body>
    <div>
        <img src="logo.png" alt="Logo">
    </div>
    <div class="update-container">
    <h2>Update Admin Details</h2>
    <div class="update-form">
        <form name="adminForm" method="POST" action="update_admin.php" onsubmit="return validateAdminForm()">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo $admin['first_name']; ?>" required autocomplete="off"><br>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo $admin['last_name']; ?>" required autocomplete="off"><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $admin['email']; ?>" required autocomplete="off"><br>

            <label>Phone No.:</label>
            <input type="text" name="phone_no" value="<?php echo $admin['phone_no']; ?>" required autocomplete="off"><br>

            <label>Current Password:</label>
            <input type="password" name="current_password" required autocomplete="off"><br>

            <label>New Password (Optional):</label>
            <input type="password" name="new_password" autocomplete="off"><br>

            <!-- Buttons fixed at the bottom -->
            <div class="button-container">
                <button type="submit">Update</button>
                <button type="button" onclick="window.location.href='admin_dashboard.php'">Back</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
