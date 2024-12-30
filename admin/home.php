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

// logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f7f7f7;
        }

        h1 {
            margin-bottom: 20px;
        }

        .container {
            margin: 20px 0px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            width: 800px;
            max-width: 90%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .card {
            height: 150px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: #333;
        }

        .card:hover {
            background-color: #f2f2f2;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin: 0;
        }

        .btn-logout {
            margin-top: 20px;
            padding: 10px 15px;
            font-size: medium;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Admin panel</h1>
        <form action="" method="post">
            <button class="btn-logout" type="submit" name="logout">Logout</button>
        </form>
    </div>

    <div class="container">
        <a href="add-product.php" class="card">
            <h2>Add New Product</h2>
        </a>
        <a href="product-manage.php" class="card">
            <h2>Manage Products</h2>
        </a>
        <a href="return-product-manage.php" class="card">
            <h2>Manage return orders</h2>
        </a>
        <a href="pending-order-manage.php" class="card">
            <h2>Manage orders</h2>
        </a>
        <a href="all-order.php" class="card">
            <h2>View all orders</h2>
        </a>
    </div>

    <h1>Discount section</h1>
    <div class="container">
        <a href="discount-buyxgety.php" class="card">
            <h2>Add Buy X Get Y</h2>
        </a>
    </div>
</body>

</html>