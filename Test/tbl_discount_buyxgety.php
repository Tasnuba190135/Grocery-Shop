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
$sql = "CREATE TABLE tbl_discount_buyxgety (
    id INT AUTO_INCREMENT PRIMARY KEY,
    p_id INT,
    buy INT,
    get INT,
    status INT,
    discount_statement VARCHAR(255),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table tbl_discount_buyxgety created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


// SQL to alter table
// $sql = "ALTER TABLE tbl_discount_buyxgety
//         ADD COLUMN discount_statement VARCHAR(255) AFTER status";

// if ($conn->query($sql) === TRUE) {
//     echo "Columns added successfully";
// } else {
//     echo "Error adding columns: " . $conn->error;
// }

$conn->close();
?>

<!-- end -->