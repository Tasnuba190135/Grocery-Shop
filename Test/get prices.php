<?php
// Database connection details
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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Total Prices</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Order Total Prices from Order ID 19 to 35</h1>

    <?php
    for ($order_id = 19; $order_id <= 35; $order_id++) {
        // Fetch order details for the given order ID
        $sql = "SELECT * FROM tbl_order WHERE id = $order_id";
        $result = $conn->query($sql);

        if ($result->num_rows == 0) {
            echo "<h2>Order ID: $order_id</h2>";
            echo "<p>Order not found.</p>";
            continue;
        }

        $order = $result->fetch_assoc();

        $productIds = explode(',', $order['p_id']);
        $quantities = explode(',', $order['quantity']);

        $totalPrices = [];
        $totalAmount = 0;

        foreach ($productIds as $key => $productId) {
            $quantity = $quantities[$key];

            $productSql = "SELECT product_title, price FROM tbl_product WHERE id = $productId";
            $productResult = $conn->query($productSql);

            if ($productResult->num_rows > 0) {
                $productRow = $productResult->fetch_assoc();
                $productTitle = $productRow['product_title'];
                $perProductPrice = $productRow['price'];
                $itemTotalPrice = $perProductPrice * $quantity;
                $totalAmount += $itemTotalPrice;

                $totalPrices[] = [
                    'product_title' => $productTitle,
                    'quantity' => $quantity,
                    'per_product_price' => $perProductPrice,
                    'item_total_price' => $itemTotalPrice
                ];
            }
        }

        echo "<h2>Order ID: $order_id</h2>";
        echo "<table>";
        echo "<tr><th>Product Title</th><th>Quantity</th><th>Per Product Price (BDT)</th><th>Item Total Price (BDT)</th></tr>";
        foreach ($totalPrices as $priceDetails) {
            echo "<tr>";
            echo "<td>" . $priceDetails['product_title'] . "</td>";
            echo "<td>" . $priceDetails['quantity'] . "</td>";
            echo "<td>" . number_format($priceDetails['per_product_price'], 2) . "</td>";
            echo "<td>" . number_format($priceDetails['item_total_price'], 2) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p>Total Amount for Order ID $order_id: " . number_format($totalAmount, 2) . " BDT</p>";
    }

    $conn->close();
    ?>

</body>

</html>
