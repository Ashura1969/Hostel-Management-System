<?php
$servername = "localhost";
$username = "root"; // Change if necessary
$password = ""; // Change if necessary
$dbname = "hms_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert test data into residents
$sql = "INSERT INTO residents (first_name, last_name, password, age, school, address, email, phone_no) 
        VALUES ('John', 'Doe', '".password_hash("password123", PASSWORD_BCRYPT)."', 22, 'XYZ University', 
                '123 Main St', 'john@example.com', '1234567890')";

if ($conn->query($sql) === TRUE) {
    echo "Test resident added successfully.<br>";
} else {
    echo "Error: " . $conn->error . "<br>";
}

// Insert test data into admin
$sql = "INSERT INTO admin (first_name, last_name, password, email, phone_no) 
        VALUES ('Admin', 'User', '".password_hash("adminpass", PASSWORD_BCRYPT)."', 'admin@example.com',
                 '0987654321')";

if ($conn->query($sql) === TRUE) {
    echo "Test admin added successfully.<br>";
} else {
    echo "Error: " . $conn->error . "<br>";
}

// Insert test data into hostel_expenses
$sql = "INSERT INTO hostel_expenses (expense_date, amount, description) 
        VALUES (CURDATE(), 500.00, 'Electricity Bill')";

if ($conn->query($sql) === TRUE) {
    echo "Test expense added successfully.<br>";
} else {
    echo "Error: " . $conn->error . "<br>";
}

// Close connection
$conn->close();
?>
