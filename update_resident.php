<?php
session_start();
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "hms_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the user is logged in
if (!isset($_SESSION['resident_id'])) {
    header("Location: resident_login.php");
    exit();
}

// Fetch resident details from the database
$resident_id = $_SESSION['resident_id'];
$resident_sql = "SELECT * FROM residents WHERE id = '$resident_id'";
$resident_result = mysqli_query($conn, $resident_sql);
$resident = mysqli_fetch_assoc($resident_result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $phone_no = trim($_POST['phone_no']);
    $age = trim($_POST['age']);
    $school = trim($_POST['school']);
    $address = trim($_POST['address']);
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);

    // Server-side validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_no) || empty($age) || empty($school) || empty($address)) {
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

    // Check if current password is provided and matches the stored password
    if (!empty($current_password)) {
        if (!password_verify($current_password, $resident['password'])) {
            echo "<script>alert('Current password is incorrect!'); window.history.back();</script>";
            exit();
        }

        // If new password is provided, validate it
        if (!empty($new_password) && strlen($new_password) >= 6) {
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
        } elseif (!empty($new_password)) {
            echo "<script>alert('New password must be at least 6 characters long!'); window.history.back();</script>";
            exit();
        }
    }

    // Prepare the update SQL query
    $update_sql = "UPDATE residents SET first_name=?, last_name=?, email=?, phone_no=?, age=?, school=?, address=?";

    if (!empty($hashed_new_password)) {
        $update_sql .= ", password=?";
    }

    $update_sql .= " WHERE id=?";

    // Prepare the statement
    $stmt = $conn->prepare($update_sql);

    // Bind parameters, checking if a new password is being provided
    if (!empty($hashed_new_password)) {
        $stmt->bind_param("ssssisssi", $first_name, $last_name, $email, $phone_no, $age, $school, $address, $hashed_new_password, $resident_id);
    } else {
        $stmt->bind_param("ssssissi", $first_name, $last_name, $email, $phone_no, $age, $school, $address, $resident_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>alert('Resident details updated successfully!'); window.location.href='resident_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating details.');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Resident</title>
    <script src="validate_resident.js"></script>
    <link rel="stylesheet" href="update.css">
</head>
<body>
    <div>
        <img src="logo.png" alt="Logo">
    </div>
    <div class="update-container">
        <h2>Update Resident Details</h2>
        <div class="update-form">
        <form name="residentForm" method="POST" action="update_resident.php" onsubmit="return validateResidentForm()">
            <label>First Name:</label>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($resident['first_name']); ?>" required><br>

            <label>Last Name:</label>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($resident['last_name']); ?>" required><br>

            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($resident['email']); ?>" required><br>

            <label>Phone No.:</label>
            <input type="text" name="phone_no" value="<?php echo htmlspecialchars($resident['phone_no']); ?>" required><br>

            <label>Age:</label>
            <input type="number" name="age" value="<?php echo htmlspecialchars($resident['age']); ?>" required><br>

            <label>School:</label>
            <input type="text" name="school" value="<?php echo htmlspecialchars($resident['school']); ?>" required><br>

            <label>Address:</label>
            <textarea name="address" required><?php echo htmlspecialchars($resident['address']); ?></textarea><br>

            <!-- Password Fields (Optional) -->
            <label>Current Password:</label>
            <input type="password" name="current_password" required><br>

            <label>New Password:</label>
            <input type="password" name="new_password"><br>

            <div class="button-container">
                <button type="submit">Update</button>
                <button type="button" onclick="window.location.href='resident_dashboard.php'">Back</button>
            </div>
        </form>
        </div>
    </div>
</body>
</html>
