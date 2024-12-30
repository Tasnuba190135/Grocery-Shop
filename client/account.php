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

// Fetch user details from tbl_user based on the user's ID stored in the session
$user_id = $_SESSION['user']['id'];
$sql = "SELECT * FROM tbl_user WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $fullname = $row['fullname'];
    $email = $row['email'];
    $address = $row['address'];
    $region = $row['region'];
} else {
    // Handle error if user data is not found
}

// Handle update address form submission
if (isset($_POST['update_address'])) {
    $new_address = $_POST['new_address'];
    $new_region = $_POST['new_region'];

    // Update the address in tbl_user
    $update_sql = "UPDATE tbl_user SET address = '$new_address', region = '$new_region' WHERE id = $user_id";
    if ($conn->query($update_sql) === TRUE) {
        // Update successful
        $address = $new_address;
        $region = $new_region;
    } else {
        // Handle update error
    }
}

// Handle logout button click
if (isset($_POST['logout'])) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to the login page
    exit();
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="../css/account.css">
</head>

<body>
    <!-- ---------navbar  -->
    <?php include_once 'navbar.php'; ?>

    <div class="container">
        <h1>My Account</h1>
        <div class="account-page">
            <nav class="sidebar">
                <ul>
                    <li><a href="#dashboard">Dashboard</a></li>
                    <li><a href="#orders">Orders</a></li>
                    <li><a href="#address">Address</a></li>
                </ul>
            </nav>
            <div class="content">
                <section id="dashboard">
                    <h2>Dashboard</h2>
                    <p>Welcome to your account dashboard. Here you can view your recent activity and update your account information.</p>
                    <p>Full name: <?php echo $fullname; ?></p>
                    <p>Email: <?php echo $email; ?></p>
                </section>
                <section id="orders">
                    <h2>Orders</h2>
                    <p>Here are your order details.</p>
                    <!-- Sample order details -->
                    <ul>
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

                        // Assuming the user ID is stored in the session
                        // $u_id = $_SESSION['user_id'];
                        $u_id = 1;

                        // Fetch order details for the logged-in user
                        $sql = "SELECT * FROM tbl_order WHERE u_id = $user_id ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $orderId = $row['id'];
                                $status = $row['status'];
                                echo "<li>Order #$orderId - $status - <a href='order-details.php?order_id=$orderId'>view details</a></li>";
                            }
                        } else {
                            echo "<li>No orders found.</li>";
                        }

                        $conn->close();
                        ?>
                    </ul>
                </section>
                <section id="address">
                    <h2>Address</h2>
                    <form method="post">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="new_address" placeholder="Enter your address" value="<?php echo $address; ?>">

                        <label for="country">Region</label>
                        <select id="country" name="new_region">
                            <option value="us" <?php if ($region == 'us') echo 'selected'; ?>>United States</option>
                            <option value="uk" <?php if ($region == 'uk') echo 'selected'; ?>>United Kingdom</option>
                            <option value="as" <?php if ($region == 'as') echo 'selected'; ?>>Asia</option>
                            <option value="af" <?php if ($region == 'af') echo 'selected'; ?>>Africa</option>
                            <option value="au" <?php if ($region == 'au') echo 'selected'; ?>>Australia</option>
                            <!-- Add more options as needed -->
                        </select>

                        <button type="submit" name="update_address">Update Address</button>
                    </form>
                </section>
                <form method="post">
                    <button class="logout" name="logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- -------------footer -->
    <?php include_once 'footer.php'; ?>

    <?php
    // Redirect to the orders section if an order_id parameter is present in the URL
    if (isset($_GET['order_id'])) {
        echo '<script>
                window.onload = function() {
                    window.location.hash = "#orders";
                };
              </script>';
    }
    ?>
</body>

</html>