<?php
// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
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

    // Prepare and bind the SQL statement to insert data into tbl_user
    $stmt = $conn->prepare("INSERT INTO tbl_user (fullname, email, password, address, region) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $password, $address, $region);

    // Set parameters and execute the statement
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $region = $_POST['region'];

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Account created successfully";
        // echo "New record created successfully";
        // Redirect to login page or any other page after successful registration
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>


<body class="bg-1">

    <!-- ---------navbar  -->
    <?php include_once 'navbar.php' ?>

    <div class="container" style="background-color:#aacdcf;">
        <h1 style="text-align:center">Registration Form</h1>
        <p style="text-align:center">Please fill in this form to create an account.</p>
        <hr>

        <form action="" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
            </div>
            <!-- <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="Address:">
            </div>  -->
            <!-- <div class="form-group">
                <input type="text" class="form-control" name="Contact" placeholder="Contact No:">
            </div>  -->
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="address">
            </div>
            <div class="form-group">
                <label for="country">Region</label>
                <select id="country" name="region">
                    <option value="us">United States</option>
                    <option value="uk">United Kingdom</option>
                    <option value="as">Asia</option>
                    <option value="af">Africa</option>
                    <option value="as">Australia</option>
                    <!-- Add more options as needed -->
                </select>
            </div>


            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>

        <div>
            <p>Already Registered <a href="login.php">Login Here</a></p>
        </div>
    </div>


    <!-- -------------footer -->
    <?php include_once 'footer.php' ?>
</body>

</html>