<?php
if (isset($_POST['update'])) {
    $id = $_POST['update'];
    $productTitle = $_POST['productTitle'];
    $productDescription = $_POST['productDescription'];
    $productStock = $_POST['stockQuantity'];
    $price = $_POST['price'];

    $servername = "localhost"; // Change this to your database server
    $username = "root"; // Change this to your database username
    $password = ""; // Change this to your database password
    $dbname = "db_grocery"; // Change this to your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "UPDATE tbl_product SET product_title='$productTitle', product_description='$productDescription', product_stock='$productStock', price='$price' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "done";
    }

    $conn->close();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        tr:hover {
            background-color: #ddd;
        }
        
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
<?php include_once'navbar.php'; ?>
    <h1>Product List</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Product Title</th>
            <th>Product Description</th>
            <th>Product Stock</th>
            <th>Product Price</th>
            <th>Created</th>
            <th>Last Modified</th>
            <th>Action</th>
        </tr>
        <?php
        $servername = "localhost"; // Change this to your database server
        $username = "root"; // Change this to your database username
        $password = ""; // Change this to your database password
        $dbname = "db_grocery"; // Change this to your database name

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM tbl_product";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<form method='post'>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td><input type='text' class='input-field' name='productTitle' value='" . $row["product_title"] . "'></td>";
                echo "<td><input type='text' class='input-field' name='productDescription' value='" . $row["product_description"] . "'></td>";
                echo "<td><input type='number' class='input-field' name='stockQuantity' value='" . $row["product_stock"] . "'></td>";
                echo "<td><input type='number' class='input-field' name='price' value='" . $row["price"] . "'></td>";
                echo "<td>" . $row["created"] . "</td>";
                echo "<td>" . $row["last_modified"] . "</td>";
                echo "<td><button class='edit-btn' type='submit' name='update' value='" . $row["id"] . "'>Update</button>";
                echo "</form>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </table>
    <script>
        function editProduct(productId) {
            // Redirect to the edit product page with the product ID
            window.location.href = "edit-product.php?id=" + productId;
        }
    </script>
</body>

</html>