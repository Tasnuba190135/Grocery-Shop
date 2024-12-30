<?php
$servername = "localhost";
$username = "root"; // Change this to your MySQL username
$password = ""; // Change this to your MySQL password
$dbname = "db_grocery"; // Change this to your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE tbl_return (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status INT,
    order_id INT,
    p_id VARCHAR(255),
    quantity VARCHAR(255),
    discount_p_id VARCHAR(255),
    discount_quantity VARCHAR(255),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table tbl_return created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();

// end here