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
$sql = "CREATE TABLE tbl_product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_image_name VARCHAR(255),
    product_title VARCHAR(255) NOT NULL,
    product_description TEXT,
    product_stock INT NOT NULL,
    price INT NOT NULL,
    created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_modified DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table tbl_product created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


// SQL queries to alter the table

// $sql = "ALTER TABLE tbl_product 
//         CHANGE COLUMN price price FLOAT AFTER product_stock";

// // Execute the queries
// if ($conn->query($sql) === TRUE) {
//     echo "Table tbl_order altered successfully";
// } else {
//     echo "Error altering table: " . $conn->error;
// }


$conn->close();
?>

<!--  -->