<?php

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the 'updateCart' button is clicked
if (isset($_POST['updateCart'])) {
    // Update the session with new quantities
    $tmpCart = array();
    foreach ($_POST['quantity'] as $productId => $newQuantity) {
        if ($newQuantity != 0) {
            $tmpCart[$productId] = $newQuantity;
        }
    }
    $_SESSION['cart'] = $tmpCart;
    // Redirect to refresh the page
    // header("Location: carts.php");

    // JavaScript code to redirect with a query parameter
    $msg = "Cart updated";
    echo '<script type="text/javascript">';
    echo 'window.location.href = "carts.php?msg=" + encodeURIComponent("' . $msg . '");';
    echo '</script>';
    exit();
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

        .total-price {
            text-align: right;
            font-size: 1.2em;
            margin-top: 20px;
        }

        .checkout-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
        }

        .update-btn {
            margin-top: 20px;
            padding: 20px 30px;
            font-size: medium;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-btn:hover {
            background-color: #45a049;
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

            if (isset($_GET['msg'])) {
                $msg = $_GET['msg'];
                // Use $msg as needed
                echo "<h3 style='color: red; text-align: center;'>" . htmlspecialchars($msg) . "</h3>";
            }

            echo "<form method='post'>";
            echo "<table>";
            echo "<tr><th>Product</th><th>Quantity</th><th>Per Product Price</th><th>Amount</th></tr>";

            foreach ($_SESSION['cart'] as $productId => $quantity) {
                // Fetch product data from the database based on the product ID
                $sql = "SELECT * FROM tbl_product WHERE id = $productId";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Calculate per product price
                        $perProductPrice = $row['price'];
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
                                $free_items = "+ $free_items_count Free";
                            }
                        }

                        // Output item details
                        echo "<tr>";
                        echo "<td>" . $row['product_title'] . "</td>";
                        echo "<td><input type='number' name='quantity[$productId]' value='$quantity' min='0' max='" . $row['product_stock'] . "'><br>" . ($free_items ? "<span style='color: green;'>$free_items</span><br>" : "") . ($discount_info ? "<span style='color: green;'>$discount_info</span>" : "") . "</td>";
                        echo "<td>" . number_format($perProductPrice, 2) . " BDT</td>";
                        echo "<td>" . $itemTotalPrice . " BDT</td>";
                        echo "</tr>";
                    }
                }
            }

            echo "</table>";
            echo "<div class='total-price'>Total Price: " . number_format($totalPrice, 2) . " BDT</div>";
            echo "<button class='update-btn' type='submit' name='updateCart'>Update Cart</button>";
            echo "</form>";

            // Button to redirect to checkout page
            echo "<a href='checkout.php' class='checkout-btn'>Proceed to Checkout</a>";
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
