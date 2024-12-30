<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_grocery";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create table
$sql = "CREATE TABLE tbl_order (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(255),
    u_id INT,
    p_id VARCHAR(255),
    quantity VARCHAR(255),
    p_price VARCHAR(255),
    discount_p_id VARCHAR(255),
    discount_quantity VARCHAR(255),
    total_price FLOAT,
    address VARCHAR(255),
    region VARCHAR(255),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table tbl_order created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

// SQL to alter table
// $sql = "ALTER TABLE tbl_order
//         ADD COLUMN total_price INT";

// if ($conn->query($sql) === TRUE) {
//     echo "Columns added successfully";
// } else {
//     echo "Error adding columns: " . $conn->error;
// }


// SQL queries to alter the table
// $sql1 = "ALTER TABLE tbl_order 
//         ADD COLUMN p_price FLOAT AFTER quantity, 
//         ADD COLUMN discount_p_id VARCHAR(255) AFTER p_price, 
//         ADD COLUMN d_quantity VARCHAR(255) AFTER discount_p_id";

// $sql2 = "ALTER TABLE tbl_order 
//         CHANGE COLUMN total_price total_price FLOAT AFTER d_quantity";

// // Execute the queries
// if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
//     echo "Table tbl_order altered successfully";
// } else {
//     echo "Error altering table: " . $conn->error;
// }


$conn->close();


?>
<!--  -->