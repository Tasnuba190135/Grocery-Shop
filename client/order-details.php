<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if order_id is set in the URL
if (!isset($_GET['order_id'])) {
    die("Order ID not specified.");
}

$order_id = $_GET['order_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_grocery";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch order details for the given order ID
$sql = "SELECT * FROM tbl_order WHERE id = $order_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Order not found.");
}

$order = $result->fetch_assoc();

$address = $order['address'];
$selectedRegion = $order['region'];
$regionFullNames = [
    "us" => "United States",
    "uk" => "United Kingdom",
    "as" => "Asia",
    "af" => "Africa",
    "au" => "Australia"
];
$selectedRegionFullName = isset($regionFullNames[$selectedRegion]) ? $regionFullNames[$selectedRegion] : '';


$productIds = explode(',', $order['p_id']);
$quantities = explode(',', $order['quantity']);
$discountProductIds = explode(',', $order['discount_p_id']);
$discountQuantities = explode(',', $order['d_quantity']);
$orderStatus = $order['status'];

// Calculate the difference between the current time and the order creation time
$orderCreated = $order['created']; // Assuming there is a 'created_at' column in the tbl_order table
$currentDate = new DateTime();
$orderDate = new DateTime($orderCreated);
$interval = $currentDate->diff($orderDate);
$daysSinceOrder = $interval->days;

$msg = '';

// If the cancel button is clicked
if (isset($_POST['cancel'])) {
    // Update the order status to 'cancelled'
    $updateSql = "UPDATE tbl_order SET status = 'cancelled' WHERE id = $order_id";
    if ($conn->query($updateSql) === TRUE) {
        // Restore stock for each product in the order
        for ($i = 0; $i < count($productIds); $i++) {
            $productId = $productIds[$i];
            $quantity = $quantities[$i];
            // Update product stock
            $updateStockSql = "UPDATE tbl_product SET product_stock = product_stock + $quantity WHERE id = $productId";
            $conn->query($updateStockSql);
        }
        // Restore stock for each product in the order's discount
        for ($i = 0; $i < count($discountProductIds); $i++) {
            $productId = $discountProductIds[$i];
            $quantity = $discountQuantities[$i];
            // Update product stock
            $updateStockSql = "UPDATE tbl_product SET product_stock = product_stock + $quantity WHERE id = $productId";
            if (is_numeric($productId)) {
                $conn->query($updateStockSql);
            }  
        }
        $msg = "Order has been successfully cancelled.";
        $orderStatus = "cancelled";
    } else {
        echo "Error updating order status: " . $conn->error;
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-price {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .return-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: medium;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .return-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <!-- ---------navbar  -->
    <?php include_once 'navbar.php'; ?>

    <div class="container">
        <h1>Order Details # <?php echo $order_id; ?> </h1>
        <p> <?php
            echo $msg;
            $msg = "";
            ?> </p>
        <table>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th></th>
                <th>Per Product Price</th>
                <th>Item Total</th>
            </tr>
            <?php
            $totalPrice = 0;

            for ($i = 0; $i < count($productIds); $i++) {
                $productId = $productIds[$i];
                $quantity = $quantities[$i];

                // Check if there's a discount quantity for this product
                $discountIndex = array_search($productId, $discountProductIds);
                $discountQuantity = ($discountIndex !== false) ? $discountQuantities[$discountIndex] : 0;

                $sql = "SELECT * FROM tbl_product WHERE id = $productId";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $productTitle = $row['product_title'];
                        $perProductPrice = $row['price'];
                        $itemTotalPrice = $perProductPrice * $quantity;
                        $totalPrice += $itemTotalPrice;

                        // Determine the discount information
                        if ($discountQuantity > 0) {
                            $discountInfo = "+ " . $discountQuantity . " free";
                        } else {
                            $discountInfo = "None";
                        }

                        echo "<tr>";
                        echo "<td>$productTitle</td>";
                        echo "<td>$quantity</td>";
                        echo "<td>$discountInfo</td>";
                        echo "<td>" . number_format($perProductPrice, 2) . " BDT</td>";
                        echo "<td>" . number_format($itemTotalPrice, 2) . " BDT</td>";
                        echo "</tr>";
                    }
                }
            }
            ?>

        </table>

        <div class='total-price'>Total Price: <?php echo number_format($totalPrice, 2); ?> BDT</div>

        <!-- // Output address and other details -->
        <?php
        // Define the shipping discounts for each region
        $shippingDiscounts = [
            "us" => 100,
            "uk" => 50,
            "as" => 10,
            "af" => 0,
            "au" => 90 // Added Australia with a discount of 90
        ];

        // Retrieve the shipping discount for the selected region
        $shippingDiscount = isset($shippingDiscounts[$selectedRegion]) ? $shippingDiscounts[$selectedRegion] : 0;

        // Calculate the total payable amount with the shipping discount applied
        $totalPayableAmount = $totalPrice + 200 - $shippingDiscount;

        // Output the shipping discount and total payable amount
        echo "<h2>Shipping Details</h2>";
        echo "<table>";
        echo "<tr><th>Shipping address</th><td> " . $address . " (" . $selectedRegion . ") </td></tr>";
        echo "<tr><th>Region </th><td> " . $selectedRegionFullName . " </td></tr>";
        echo "<tr><th>Shipping charge</th><td> " . 200 . " BDT</td></tr>";
        echo "<tr><th>Shipping Discount</th><td>- " . number_format($shippingDiscount, 2) . " BDT</td></tr>";
        echo "<tr><th><h2>Total Payable Amount</h2></th><td><h2>" . number_format($totalPayableAmount, 2) . " BDT</h2></td></tr>";
        echo "</table>";
        ?>

        <?php if ($orderStatus === 'pending') : ?>
            <!-- Cancel request form -->
            <form action="" method="post">
                <button type="submit" name="cancel" value="<?php echo $order_id; ?>" class="return-btn">Cancel order</button>
            </form>
            <!-- $orderStatus === 'processing' is not used here -->
        <?php elseif ($orderStatus === 'completed' && $daysSinceOrder <= 7) : ?>
            <a class="return-btn" href="return-form.php?order_id=<?php echo $order_id; ?>">Request to return (Tap here)</a>
        <?php elseif ($orderStatus !== 'completed' && $daysSinceOrder > 7) : ?>
            <p>Return request cannot be processed as the order is not completed and older than 7 days.</p>
        <?php endif; ?>


    </div>

    <!-- -------------footer -->
    <?php include_once 'footer.php'; ?>

</body>

</html>

<?php
$conn->close();
?>

<script>
    // Reload the page after form submission
    function reloadPage() {
        location.reload();
    }
</script>