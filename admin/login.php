<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form is submitted
if (isset($_POST['login'])) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_grocery"; // Replace with your actual database name
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set parameters and execute the statement
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email == "admin@admin") {
        // User found, verify password
        if ($password == "admin") {
            // Redirect to index.php or any other page after successful login
            $_SESSION['admin'] = "admin";
            header("Location: home.php");
            exit();
        } else {
            // Password is incorrect
            $loginError = "Invalid email or password";
        }
    } else {
        // User not found
        $loginError = "Invalid email or password";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <h1>Admin panel</h1>
    <div class="container" style="background-color:#aacdcf;">
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="from-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
            <?php if (isset($loginError)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $loginError; ?>
                </div>
            <?php endif; ?>
        </form>
        <div>
            <!-- <p>Not registered yet <a href="registration.php">Register Here</a></p> -->
        </div>
    </div>

    <!-- -------------footer -->
</body>

</html>