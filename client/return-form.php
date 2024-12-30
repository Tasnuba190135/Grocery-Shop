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

$productIds = explode(',', $order['p_id']);
$quantities = explode(',', $order['quantity']);
$discountProductIds = explode(',', $order['discount_p_id']);
$discountQuantities = explode(',', $order['d_quantity']);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $returnQuantities = $_POST['quantity'];
    $returnDiscountQuantities = $_POST['free_quantity'];

    // Prepare data for insertion
    $p_ids = [];
    $quantitiesArr = [];
    $discount_p_ids = [];
    $discount_quantitiesArr = [];

    foreach ($returnQuantities as $productId => $quantity) {
        if ($quantity > 0) {
            $p_ids[] = $productId;
            $quantitiesArr[] = $quantity;
        }
    }

    foreach ($returnDiscountQuantities as $productId => $quantity) {
        if ($quantity > 0) {
            $discount_p_ids[] = $productId;
            $discount_quantitiesArr[] = $quantity;
        }
    }

    if (!empty($p_ids) || !empty($discount_p_ids)) {
        $p_ids_str = implode(',', $p_ids);
        $quantities_str = implode(',', $quantitiesArr);
        $discount_p_ids_str = implode(',', $discount_p_ids);
        $discount_quantities_str = implode(',', $discount_quantitiesArr);

        // Insert into tbl_return
        $sql = "INSERT INTO tbl_return (status, order_id, p_id, quantity, discount_p_id, discount_quantity) 
                VALUES (0, $order_id, '$p_ids_str', '$quantities_str', '$discount_p_ids_str', '$discount_quantities_str')";

        if ($conn->query($sql) === TRUE) {
            // Update tbl_order status
            $updateOrderStatusSql = "UPDATE tbl_order SET status = 'return request' WHERE id = $order_id";
            if ($conn->query($updateOrderStatusSql) === TRUE) {
                echo "Return request has been submitted.";
                // Redirect to account page with the order ID
                header("Location: account.php?order_id=$orderId#order");
                exit();
            } else {
                echo "Error updating order status: " . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "No items to return.";
    }

    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Form</title>
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

        .submit-btn {
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

        .submit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Return Form for Order #<?php echo $order_id; ?></h1>
        <form action="" method="post">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Free Quantity</th>
                </tr>
                <?php
                for ($i = 0; $i < count($productIds); $i++) {
                    $productId = $productIds[$i];
                    $quantity = $quantities[$i];

                    // Check if there's a discount quantity for this product
                    $discountIndex = array_search($productId, $discountProductIds);
                    $discountQuantity = ($discountIndex !== false) ? $discountQuantities[$discountIndex] : 0;

                    $sql = "SELECT * FROM tbl_product WHERE id = $productId";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $productTitle = $row['product_title'];
                        $productPrice = $row['price'];

                        echo "<tr>";
                        echo "<td>$productTitle</td>";
                        echo "<td>" . number_format($productPrice, 2) . " BDT</td>";
                        echo "<td><input type='number' name='quantity[$productId]' value='$quantity' min='0' max='$quantity'></td>";
                        if ($discountQuantity > 0) {
                            echo "<td><input type='number' name='free_quantity[$productId]' value='$discountQuantity' min='0' max='$discountQuantity'></td>";
                        }
                        echo "</tr>";
                    }
                }
                ?>
            </table>
            <button type="submit" class="submit-btn">Submit Return Request</button>
        </form>
    </div>

</body>

</html>

<?php
$conn->close();
?>