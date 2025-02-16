<?php
$servername = "localhost";
$username = "root"; // Change this to your database username
$password = ""; // Change this to your database password
$dbname = "hms_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db($dbname);

// Create Residents Table
$sql = "CREATE TABLE IF NOT EXISTS residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    school VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_no VARCHAR(20) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Residents table created successfully<br>";
} else {
    echo "Error creating residents table: " . $conn->error;
}

// Create Admin Table
$sql = "CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone_no VARCHAR(20) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Admin table created successfully<br>";
} else {
    echo "Error creating admin table: " . $conn->error;
}

// Create Hostel Expenses Table
$sql = "CREATE TABLE IF NOT EXISTS hostel_expenses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expense_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Hostel expenses table created successfully<br>";
} else {
    echo "Error creating hostel expenses table: " . $conn->error;
}

// Close connection
$conn->close();
?>
