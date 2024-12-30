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

    // Prepare and bind the SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT id, fullname, email, password FROM tbl_user WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Set parameters and execute the statement
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if ($password == $row['password']) {
            // Password is correct, start a new session
            $_SESSION['user'] = array(
                'id' => $row['id'],
                'fullname' => $row['fullname'],
                'email' => $row['email']
            );
            // Redirect to index.php or any other page after successful login
            header("Location: ../index.php");
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
    <!-- ---------navbar  -->
    <?php include_once 'navbar.php' ?>

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
            <p>Not registered yet <a href="registration.php">Register Here</a></p>
        </div>
    </div>

    <!-- -------------footer -->
    <?php include_once 'footer.php' ?>
</body>

</html>