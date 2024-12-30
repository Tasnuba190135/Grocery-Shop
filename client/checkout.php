<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

if (isset($_POST['order'])) {
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

    // Check if the cart session exists and is not empty
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $productIds = [];
        $quantities = [];
        $totalPrice = 0;

        foreach ($_SESSION['cart'] as $productId => $quantity) {
            // Store product IDs and quantities
            $productIds[] = $productId;
            $quantities[] = $quantity;

            $sql = "SELECT * FROM tbl_product WHERE id = $productId";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Calculate per product price
                    $perProductPrice = $row['price'];
                    $itemTotalPrice = $perProductPrice * $quantity;
                    $totalPrice += $itemTotalPrice;
                }
            }
        }

        // Convert arrays to comma-separated strings
        $productIdsString = implode(",", $productIds);
        $quantitiesString = implode(",", $quantities);

        // Insert into tbl_order
        $status = "pending"; // Default status
        $user_id = $_SESSION['user']['id'];

        // Get user address and region
        $user_query = "SELECT address, region FROM tbl_user WHERE id = $user_id";
        $user_result = $conn->query($user_query);

        if ($user_result->num_rows > 0) {
            $user_row = $user_result->fetch_assoc();
            $address = $user_row['address'];
            $selectedRegion = $user_row['region'];
        } else {
            // Handle error if user data is not found
            die("User data not found.");
        }

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

        // Set discount part
        $productPricesString = $_SESSION['price'];
        $discountProductIdsString = isset($_SESSION['d_p_id']) ? $_SESSION['d_p_id'] : '';
        $discountQuantitiesString = isset($_SESSION['d_quantity']) ? $_SESSION['d_quantity'] : '';

        // Insert order into tbl_order
        $insertSql = "INSERT INTO tbl_order (status, u_id, p_id, quantity, p_price, discount_p_id, d_quantity, address, region, total_price)
                      VALUES ('$status', $user_id, '$productIdsString', '$quantitiesString', '$productPricesString', '$discountProductIdsString', '$discountQuantitiesString', '$address', '$selectedRegion', $totalPayableAmount)";

        if ($conn->query($insertSql) === TRUE) {
            $orderId = $conn->insert_id;
            echo "New order created successfully with Order ID: $orderId<br>";

            // Subtract the actual quantities from the stock
            foreach ($_SESSION['cart'] as $productId => $quantity) {
                $updateStockSql = "UPDATE tbl_product SET product_stock = product_stock - $quantity WHERE id = $productId;";

                // Execute the query for the main product stock update
                if (!$conn->query($updateStockSql)) {
                    echo "Error updating stock for product ID: $productId - " . $conn->error . "<br>";
                }
            }

            // Subtract the discount quantities from the respective product ID
            if (isset($_SESSION['d_p_id']) && isset($_SESSION['d_quantity'])) {
                $discountProductIds = explode(",", $_SESSION['d_p_id']);
                $discountQuantities = explode(",", $_SESSION['d_quantity']);
                $count = count($discountProductIds);
                // echo $count . "---------------<br>";
                for ($i = 0; $i < $count; $i++) {
                    $discountProductId = $discountProductIds[$i];
                    $discountQuantity = $discountQuantities[$i];
                    // echo $discountProductId . " " . $discountQuantity . "<br>";
                    $updateDiscountStockSql = "UPDATE tbl_product SET product_stock = product_stock - $discountQuantity WHERE id = $discountProductId";

                    // Check if the discount product ID is a valid number
                    if (is_numeric($discountProductId)) {
                        // echo $discountProductId . " " . $discountQuantity . "<br>";
                        $updateDiscountStockSql = "UPDATE tbl_product SET product_stock = product_stock - $discountQuantity WHERE id = $discountProductId";

                         if (!$conn->query($updateDiscountStockSql)) {
                            echo "Error updating discount stock for product ID: $discountProductId - " . $conn->error . "<br>";
                        }
                    } else {
                        echo "Invalid discount product ID: $discountProductId<br>";
                    }
                }
            }

            // Clear cart session variables
            unset($_SESSION['cart']);
            unset($_SESSION['d_p_id']);
            unset($_SESSION['d_quantity']);
            unset($_SESSION['price']);

            // Redirect to account page with the order ID
            header("Location: account.php?order_id=$orderId#order");
            exit();
        } else {
            echo "Error: " . $insertSql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "Your cart is empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Items</title>
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

        /* td:last-child {
            text-align: right;
        } */

        .total-price {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .confirm-order-btn {
            /* display: block; */
            margin-top: 20px;
            padding: 30px 50px;
            font-size: medium;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .confirm-order-btn:hover {
            background-color: #45a049;
        }

        .center {
            display: block;
            text-align: center;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <!-- ---------navbar  -->
    <?php include_once 'navbar.php' ?>

    <div class="container">
        <?php
        // Start the session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

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

        // Initialize variables for total price calculation
        $totalPrice = 0;

        // Check if the cart session exists and is not empty
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            // Output cart items
            echo "<h1>Cart Items</h1>";
            echo "<table>";
            echo "<tr><th>Product</th><th>Quantity</th><th>Per Product Price</th><th>Item total</th></tr>";

            $discountProductIds = [];
            $discountQuantities = [];
            $productPrices = [];

            foreach ($_SESSION['cart'] as $productId => $quantity) {

                // Fetch product data from the database based on the product ID
                $sql = "SELECT * FROM tbl_product WHERE id = $productId";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Calculate per product price
                        $perProductPrice = $row['price'];
                        $productPrices[] = $perProductPrice;

                        $itemTotalPrice = $perProductPrice * $quantity;
                        $totalPrice += $itemTotalPrice;

                        // Fetch discount details
                        $sql_discount = "SELECT * FROM tbl_discount_buyxgety WHERE p_id = $productId AND status = 1";
                        $result_discount = $conn->query($sql_discount);
                        $discount_info = '';
                        $free_items = '';

                        if ($result_discount->num_rows > 0) {
                            $row_discount = $result_discount->fetch_assoc();
                            $buy = $row_discount['buy'];
                            $get = $row_discount['get'];

                            $discount_info = "Buy $buy, Get $get Free";

                            // Calculate the number of free items based on the quantity in the cart
                            $free_items_count = floor($quantity / $buy) * $get;
                            if ($free_items_count > 0) {
                                $free_items = " + $free_items_count Free";
                            }

                            // Store discount product IDs and quantities
                            $discountProductIds[] = $productId;
                            $discountQuantities[] = $free_items_count;
                        }

                        // Output item details
                        echo "<tr>";
                        echo "<td>" . $row['product_title'] . "</td>";
                        echo "<td>" . $quantity . $free_items . "</td>";
                        echo "<td>" . number_format($perProductPrice, 2) . " BDT</td>";
                        echo "<td>" . $itemTotalPrice . " BDT</td>";
                        echo "</tr>";
                    }
                }
            }

            // Convert arrays to comma-separated strings
            $discountProductIdsString = implode(",", $discountProductIds);
            $discountQuantitiesString = implode(",", $discountQuantities);
            $productPricesString = implode(",", $productPrices);

            // echo $discountProductIdsString . "<br>";
            // echo $discountQuantitiesString . "<br>";
            // echo $productPricesString . "<br>";

            $_SESSION['price'] = $productPricesString;
            $_SESSION['d_p_id'] = $discountProductIdsString;
            $_SESSION['d_quantity'] = $discountQuantitiesString;

            echo "</table>";

            // Output total price for all items in the cart
            echo "<div class='total-price'>Total Price: " . number_format($totalPrice, 2) . " BDT</div>";

            // get from user table
            $address = "abc, xyz";
            $selectedRegion = "au";
            // get from user table
            $user_id = $_SESSION['user']['id'];
            $user_query = "SELECT address, region FROM tbl_user WHERE id = $user_id";
            $user_result = $conn->query($user_query);

            if ($user_result->num_rows > 0) {
                $user_row = $user_result->fetch_assoc();
                $address = $user_row['address'];
                $selectedRegion = $user_row['region'];
            } else {
                // Handle error if user data is not found
            }

            $regionFullNames = [
                "us" => "United States",
                "uk" => "United Kingdom",
                "as" => "Asia",
                "af" => "Africa",
                "au" => "Australia"

            ];
            $selectedRegionFullName = isset($regionFullNames[$selectedRegion]) ? $regionFullNames[$selectedRegion] : '';

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

            echo '
            <div class="center">
                <form action="" method="post">
                    <button class="confirm-order-btn" name="order">Confirm order</button>
                </form>
            </div>
            ';
        } else {
            // If cart is empty, display a message
            echo "<h2>Your cart is empty</h2>";
        }

        $conn->close();
        ?>
    </div>

    <!-- -------------footer -->
    <?php include_once 'footer.php' ?>

</body>

</html>