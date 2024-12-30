<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the 'addToCart' button is clicked
if (isset($_POST['addToCart'])) {
    // Get the product ID and quantity from the form submission
    $productId = $_POST['addToCart'];
    $quantity = $_POST['quantity'];

    // Initialize the session variables if they don't exist
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    // Update the session variables with the product ID and quantity
    $_SESSION['cart'][$productId] = $quantity;

    echo "<script>alert('Added to cart');</script>";

    // Optional: Redirect the user to another page or display a message
    // header("Location: carts.php");
    // exit(); // Stop further execution
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/single-product.css">
    <title>Product details</title>
    <style>
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

    <!-- ---------navbar  -->
    <?php include_once 'navbar.php' ?>

    <?php
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

    // Check if the 'id' parameter is set in the URL
    if (isset($_GET['id'])) {
        $productId = $_GET['id'];

        // Fetch product data from the database based on the 'id'
        $sql = "SELECT * FROM tbl_product WHERE id = $productId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of the selected product
            while ($row = $result->fetch_assoc()) {
    ?>
                <div class="picture">
                    <img src="../db/image/<?php echo $row['product_image_name']; ?>" width="400px" height="300px" alt="image">
                </div>
                <p class="text"><?php echo $row['product_title']; ?></p>

                <div style="text-align: center; margin: 10px 0px;">
                    <h3 class="color2">Price <?php echo $row['price']; ?></h3>
                </div>
                <div class="product-section">
                    <p class="details">— 𝐏𝐑𝐎𝐃𝐔𝐂𝐓 𝐃𝐄𝐓𝐀𝐈𝐋𝐒 —</p>
                    <p><?php echo $row['product_description']; ?></p>
                </div>
                <div class="product-section">
                    <h2>Available: </h2>
                    <p><?php echo $row['product_stock']; 
                        ?> items</p>
                </div>

                <?php
                // Fetch free offer from tbl_buyxgety
                $offerSql = "SELECT * FROM tbl_discount_buyxgety WHERE p_id = $productId";
                $offerResult = $conn->query($offerSql);

                if ($offerResult->num_rows > 0) {
                    // Output offer data
                    while ($offerRow = $offerResult->fetch_assoc()) {
                        echo "<div class='product-section'>";
                        echo "<h2 style='color: red;'>Free Offer:</h2>";
                        echo "<p>Buy " . $offerRow['buy'] . " and get " . $offerRow['get'] . " free!</p>";
                        // calculation of showing the free item quantities
                        $freeQuantity = 0;
                        if (isset($_SESSION['cart'][$productId])) {
                            $cartQuantity = $_SESSION['cart'][$productId];
                            $freeQuantity = floor($cartQuantity / $offerRow['buy']) * $offerRow['get'];
                        }

                        echo "<p style='color: green;'><b>Congrats! You have got free $freeQuantity items as you have added $cartQuantity items to the cart.</b></p>";
                        echo "</div>";
                        echo "</div>";
                    }
                }
                ?>

                <div class="cart-section">
                    <form id="productForm" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="stockQuantity">Quantity</label>
                            <?php
                            // Start the session if not already started
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }

                            // Check if the current product ID is in the cart session
                            $defaultQuantity = 1; // Default quantity is 1
                            if (isset($_SESSION['cart'][$row['id']])) {
                                // If the product is in the cart, set the quantity to the quantity in the cart
                                $defaultQuantity = $_SESSION['cart'][$row['id']];
                            }
                            ?>
                            <input type="number" id="quantity" name="quantity" value="<?php echo $defaultQuantity; ?>" min="0" max="<?php echo $row['product_stock']; ?>" required>
                        </div>


                        <button type="submit" name="addToCart" value="<?php echo $row['id']; ?>">Add to cart</button>
                    </form>
                </div>
    <?php
            }
        } else {
            echo "No product found with the provided ID.";
        }
    } else {
        echo "Product ID is not provided.";
    }

    $conn->close();
    ?>



    <!-- -------------footer -->
    <?php include_once 'footer.php' ?>

</body>

</html>