<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage return product</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        tr:hover {
            background-color: #ddd;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
<?php include_once'navbar.php'; ?>
    <h1>Product List</h1>
    <table>
        <?php
        $servername = "localhost"; // Change this to your database server
        $username = "root"; // Change this to your database username
        $password = ""; // Change this to your database password
        $dbname = "db_grocery"; // Change this to your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM tbl_order";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output table header
            echo "<table>";
            echo "<tr><th>Order ID</th><th>Status</th><th>User ID</th><th>Products</th><th>Created</th><th>Last Modified</th></tr>";

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                $orderId = $row['id'];
                $status = $row['status'];
                $userId = $row['u_id'];
                $productIds = explode(',', $row['p_id']);
                $quantities = explode(',', $row['quantity']);
                $created = $row['created'];
                $lastModified = $row['last_modified'];


                // Fetch product details for each product in the order
                $productsInfo = "";
                foreach ($productIds as $key => $productId) {
                    $productSql = "SELECT product_title FROM tbl_product WHERE id = $productId";
                    $productResult = $conn->query($productSql);
                    if ($productResult->num_rows > 0) {
                        $productRow = $productResult->fetch_assoc();
                        $productTitle = $productRow['product_title'];
                        $productsInfo .= "$productTitle ($quantities[$key]), ";
                    }
                }
                // Remove trailing comma and space
                $productsInfo = rtrim($productsInfo, ", ");

                // Output table row
                echo "<tr>";
                echo "<td>$orderId</td>";
                echo "<td>$status</td>";
                echo "<td>$userId</td>";
                echo "<td>$productsInfo</td>";
                echo "<td>$created</td>";
                echo "<td>$lastModified</td>";
                // echo "<td><form method='POST'><button type='submit' class='edit-btn' name='processing' value='" . $orderId . "'>Processing</button> <form method='POST'><button type='submit' class='edit-btn' style='background-color: red;' name='completed' value='" . $orderId . "'>Completed</button></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No orders found.";
        }

        $conn->close();
        ?>
    </table>

</body>

</html>