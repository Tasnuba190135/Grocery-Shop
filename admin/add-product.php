<?php

if (isset($_POST['submit'])) {

    $target_dir = "../db/image/";
    $fileName = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

    $productTitle = $_POST['productTitle'];
    $productDescription = $_POST['productDescription'];
    $productStock = $_POST['stockQuantity'];
    $price = $_POST['price'];


    $sql = "INSERT INTO tbl_product (product_image_name, product_title, product_description, product_stock, price) VALUES ('$fileName', '$productTitle', '$productDescription', $productStock, $price)";

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_grocery";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (mysqli_query($conn, $sql)) {
        echo "ok";
    }

    // Close connection
    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    <link rel="stylesheet" href="../css/add-product.css">
</head>

<body>

    <div class="container">
        <?php include_once'navbar.php'; ?>    

        <h1>Product Form</h1>

        <form id="productForm" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productTitle">Product Image</label>
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>
            <div class="form-group">
                <label for="productTitle">Product Title</label>
                <input type="text" id="productTitle" name="productTitle" required>
            </div>
            <div class="form-group">
                <label for="productDescription">Product Description</label>
                <textarea id="productDescription" name="productDescription" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="stockQuantity">Stock Quantity</label>
                <input type="number" id="stockQuantity" name="stockQuantity" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" required>
            </div>
            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>

</html>