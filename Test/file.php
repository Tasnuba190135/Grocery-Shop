<?php

if (isset($_POST['submit'])) {

    $target_dir = "uploads/";
    $file_name = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
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
        <h1>Product Form</h1>
        <form id="productForm" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="productTitle">Product Image</label>
                <input type="file" name="fileToUpload" id="fileToUpload">
            </div>

            <button type="submit" name="submit">Submit</button>
        </form>
    </div>
</body>

</html>