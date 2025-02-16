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

// Fetch hostel expenses from the database
$expenses_sql = "SELECT * FROM hostel_expenses";
$expenses_result = mysqli_query($conn, $expenses_sql);

// Calculate the total expenses
$total_expenses_sql = "SELECT SUM(amount) as total_expenses FROM hostel_expenses";
$total_expenses_result = mysqli_query($conn, $total_expenses_sql);
$total_expenses = mysqli_fetch_assoc($total_expenses_result)['total_expenses'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard</title>
    <link rel="stylesheet" href="resident_dashboard.css"> <!-- Assuming a separate CSS file -->
</head>
<body>
    <nav>
        <div>
            <img src="logo.png" alt="Logo">
        </div>
    </nav>
    <div class="container">
        <!-- Resident details container -->
        <div class="resident-details">
            <h2>Resident Details</h2>
            <p><strong>Name:</strong> <?php echo $resident['first_name'] . ' ' . $resident['last_name']; ?></p>
            <p><strong>Email:</strong> <?php echo $resident['email']; ?></p>
            <p><strong>Phone No:</strong> <?php echo $resident['phone_no']; ?></p>
            <p><strong>Age:</strong> <?php echo $resident['age']; ?></p>
            <p><strong>School:</strong> <?php echo $resident['school']; ?></p>
            <p><strong>Address:</strong> <?php echo $resident['address']; ?></p>
            <a href="update_resident.php" class="update-button">Update</a> <!-- Link to update details -->
            <a href="logout.php" class="logout-button">Logout</a> <!-- Logout link -->
        </div>

        <!-- Hostel expenses container -->
        <div class="hostel-expenses">
            <h2>Hostel Expenses</h2>
            <table>
                <thead>
                    <tr>
                        <th>Expense Date</th>
                        <th>Amount</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($expense = mysqli_fetch_assoc($expenses_result)) { ?>
                        <tr>
                            <td><?php echo $expense['expense_date']; ?></td>
                            <td><?php echo number_format($expense['amount'], 2); ?></td>
                            <td><?php echo $expense['description']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"><strong>Total Expenses:</strong></td>
                        <td><strong><?php echo number_format($total_expenses, 2); ?></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>
