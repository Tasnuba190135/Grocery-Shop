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
    <title>Manage Return Product</title>
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
    <?php include_once 'navbar.php'; ?>
    <h1>Return Product Management</h1>
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

        // Process accept and reject actions
        if (isset($_POST['accept'])) {
            $orderId = $_POST['accept'];
            $conn->begin_transaction();

            try {
                // Update return status to accepted
                $acceptReturnSql = "UPDATE tbl_return SET status=1 WHERE order_id=$orderId";
                $conn->query($acceptReturnSql);

                // Update order status to return accepted
                $acceptOrderSql = "UPDATE tbl_order SET status='return accepted' WHERE id=$orderId";
                $conn->query($acceptOrderSql);

                // Update stock of returned products
                $returnDetailsSql = "SELECT * FROM tbl_return WHERE order_id=$orderId";
                $returnDetailsResult = $conn->query($returnDetailsSql);

                if ($returnDetailsResult->num_rows > 0) {
                    $returnDetails = $returnDetailsResult->fetch_assoc();
                    $productIds = explode(',', $returnDetails['p_id']);
                    $quantities = explode(',', $returnDetails['quantity']);
                    $discountProductIds = explode(',', $returnDetails['discount_p_id']);
                    $discountQuantities = explode(',', $returnDetails['discount_quantity']);

                    for ($i = 0; $i < count($productIds); $i++) {
                        $productId = $productIds[$i];
                        $quantity = $quantities[$i];
                        $updateStockSql = "UPDATE tbl_product SET product_stock = product_stock + $quantity WHERE id = $productId";
                        $conn->query($updateStockSql);
                    }

                    for ($i = 0; $i < count($discountProductIds); $i++) {
                        $discountProductId = $discountProductIds[$i];
                        $discountQuantity = isset($discountQuantities[$i]) ? $discountQuantities[$i] : 0; // Check if index exists
                        $updateDiscountStockSql = "UPDATE tbl_product SET product_stock = product_stock + $discountQuantity WHERE id = $discountProductId";
                        if (is_numeric($discountProductId)) {
                            $conn->query($updateDiscountStockSql);
                        }
                    }

                    echo "Order $orderId return accepted successfully.";
                }

                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error updating order status: " . $conn->error;
            }
        }

        if (isset($_POST['reject'])) {
            $orderId = $_POST['reject'];
            $conn->begin_transaction();

            try {
                // Update return status to rejected
                $rejectReturnSql = "UPDATE tbl_return SET status=1 WHERE order_id=$orderId";
                $conn->query($rejectReturnSql);

                // Update order status to return rejected
                $rejectOrderSql = "UPDATE tbl_order SET status='return rejected' WHERE id=$orderId";
                $conn->query($rejectOrderSql);

                echo "Order $orderId rejected successfully.";

                $conn->commit();
            } catch (Exception $e) {
                $conn->rollback();
                echo "Error updating order status: --" . $conn->error;
            }
        }

        $sql = "SELECT o.*, r.created AS return_created, r.p_id AS return_p_id, r.quantity AS return_quantity, r.discount_p_id AS return_discount_p_id, r.discount_quantity AS return_discount_quantity
                FROM tbl_order o
                JOIN tbl_return r ON o.id = r.order_id
                WHERE r.status = 0";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Order ID</th><th>Order Time</th><th>Order Products</th><th>Return Time</th><th>Return Products</th><th>Action</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $orderId = $row['id'];
                $orderCreated = $row['created'];
                $returnCreated = $row['return_created'];

                // Order products
                $orderProductIds = explode(',', $row['p_id']);
                $orderQuantities = explode(',', $row['quantity']);
                $orderDiscountProductIds = explode(',', $row['discount_p_id']);
                $orderDiscountQuantities = explode(',', $row['d_quantity']);

                $orderProductsInfo = "";
                for ($i = 0; $i < count($orderProductIds); $i++) {
                    $productId = $orderProductIds[$i];
                    $quantity = $orderQuantities[$i];
                    $productSql = "SELECT product_title FROM tbl_product WHERE id = $productId";
                    $productResult = $conn->query($productSql);
                    if ($productResult->num_rows > 0) {
                        $productRow = $productResult->fetch_assoc();
                        $productTitle = $productRow['product_title'];
                        $orderProductsInfo .= "$productTitle ($quantity), ";
                    }
                }
                for ($i = 0; $i < count($orderDiscountProductIds); $i++) {
                    $discountProductId = $orderDiscountProductIds[$i];
                    $discountQuantity = isset($orderDiscountQuantities[$i]) ? $orderDiscountQuantities[$i] : 0; // Check if index exists
                    if (is_numeric($discountProductId)) {
                        $productSql = "SELECT product_title FROM tbl_product WHERE id = $discountProductId";
                        $productResult = $conn->query($productSql);
                        if ($productResult->num_rows > 0) {
                            $productRow = $productResult->fetch_assoc();
                            $productTitle = $productRow['product_title'];
                            $orderProductsInfo .= "$productTitle ($discountQuantity free), ";
                        }
                    }
                }
                $orderProductsInfo = rtrim($orderProductsInfo, ", ");

                // Return products
                $returnProductIds = explode(',', $row['return_p_id']);
                $returnQuantities = explode(',', $row['return_quantity']);
                $returnDiscountProductIds = explode(',', $row['return_discount_p_id']);
                $returnDiscountQuantities = explode(',', $row['return_discount_quantity']);

                $returnProductsInfo = "";
                for ($i = 0; $i < count($returnProductIds); $i++) {
                    $productId = $returnProductIds[$i];
                    $quantity = $returnQuantities[$i];
                    $productSql = "SELECT product_title FROM tbl_product WHERE id = $productId";
                    $productResult = $conn->query($productSql);
                    if ($productResult->num_rows > 0) {
                        $productRow = $productResult->fetch_assoc();
                        $productTitle = $productRow['product_title'];
                        $returnProductsInfo .= "$productTitle ($quantity), ";
                    }
                }
                for ($i = 0; $i < count($returnDiscountProductIds); $i++) {
                    $discountProductId = $returnDiscountProductIds[$i];
                    $discountQuantity = isset($returnDiscountQuantities[$i]) ? $returnDiscountQuantities[$i] : 0; // Check if index exists
                    $productSql = "SELECT product_title FROM tbl_product WHERE id = $discountProductId";
                    if (is_numeric($discountProductId)) {
                        $productResult = $conn->query($productSql);
                        if ($productResult->num_rows > 0) {
                            $productRow = $productResult->fetch_assoc();
                            $productTitle = $productRow['product_title'];
                            $returnProductsInfo .= "$productTitle ($discountQuantity free), ";
                        }
                    }
                }
                $returnProductsInfo = rtrim($returnProductsInfo, ", ");

                echo "<tr>";
                echo "<td>$orderId</td>";
                echo "<td>$orderCreated</td>";
                echo "<td>$orderProductsInfo</td>";
                echo "<td>$returnCreated</td>";
                echo "<td>$returnProductsInfo</td>";
                echo "<td><form method='POST'><button type='submit' class='edit-btn' name='accept' value='$orderId'>Accept</button> <button type='submit' class='edit-btn' style='background-color: red;' name='reject' value='$orderId'>Reject</button></form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "No return requests found.";
        }

        $conn->close();
        ?>
    </table>
</body>

</html>
