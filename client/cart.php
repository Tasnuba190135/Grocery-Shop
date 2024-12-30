<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the 'cart' session variable exists
if (isset($_SESSION['cart'])) {
    // Get the cart items from the session
    $cartItems = $_SESSION['cart'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Items</title>
</head>

<body>
    <h1>Cart Items</h1>

    <ul id="cartList">
        <?php
        // Iterate through the cart items and display each one
        foreach ($cartItems as $productId => $quantity) {
            echo "<li>Product ID: $productId, Quantity: $quantity</li>";
        }
        ?>
    </ul>

</body>

</html>
<?php
} else {
    // If the 'cart' session variable does not exist, display a message
    echo "<p>No items in the cart</p>";
}
?>
