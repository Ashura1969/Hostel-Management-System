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

// Handle delete resident
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM residents WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        echo "<script>alert('Resident deleted successfully!'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting resident.');</script>";
    }
    $stmt->close();
}

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT first_name, last_name, email, phone_no FROM admin WHERE id=?";
$stmt_admin = $conn->prepare($sql_admin);
$stmt_admin->bind_param("i", $admin_id);
$stmt_admin->execute();
$result_admin = $stmt_admin->get_result();
$admin = $result_admin->fetch_assoc();
$stmt_admin->close();

// Fetch all residents
$sql_residents = "SELECT id, first_name, last_name, age, school, address, email, phone_no FROM residents";
$result_residents = $conn->query($sql_residents);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this resident?")) {
                window.location.href = "admin_dashboard.php?delete_id=" + id;
            }
        }

        function toggleAccountDropdown() {
            var dropdown = document.getElementById("accountDropdown");
            dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
        }

        document.addEventListener("click", function(event) {
            var dropdown = document.getElementById("accountDropdown");
            var button = document.querySelector("button[onclick='toggleAccountDropdown()']");
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                dropdown.style.display = "none";
            }
        });
    </script>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>

    <!-- Navbar (Transparent) -->
    <nav class="navbar">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <div class="navbar-links">
            <a href="admin_dashboard.php">Home</a>
            <button onclick="toggleAccountDropdown()">Account ▼</button>
            <div id="accountDropdown" class="accountDropdown" style="display: none;">
                <div class="admin-box">
                    <h3><strong><?php echo isset($admin['first_name']) ? $admin['first_name'] . " " . $admin['last_name'] : "Admin User"; ?></strong></h3>
                    <p><?php echo isset($admin['email']) ? $admin['email'] : "admin@example.com"; ?></p>
                    <p><?php echo isset($admin['phone_no']) ? $admin['phone_no'] : "N/A"; ?></p>
                    <button class="update" onclick="window.location.href='update_admin.php'">Update</button>
                    <button class="logout" onclick="window.location.href='logout.php'">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="table-container">
        <h2>Resident List</h2>
        <button class="add-resident-btn" onclick="window.location.href='add_resident.php';">+ Add Resident</button>

        <!-- Table Container -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Age</th>
                            <th>School</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_residents && $result_residents->num_rows > 0): ?>
                            <?php while ($row = $result_residents->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['first_name']; ?></td>
                                    <td><?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['age']; ?></td>
                                    <td><?php echo $row['school']; ?></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['phone_no']; ?></td>
                                    <td>
                                        <button onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9">No residents found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>

</body>
</html>
