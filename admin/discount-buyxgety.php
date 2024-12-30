<?php

// To update the discount
if (isset($_POST['discount_id'])) {
    // Database connection details
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

    // Retrieve form data
    $product_id = $_POST['product_id'];
    $buy = $_POST['buy'];
    $get = $_POST['get'];
    $status = $_POST['status'];
    $discount_statement = $_POST['discount_statement'];
    $discount_id = $_POST['discount_id'];

    // Check if discount exists
    if ($discount_id == '') {
        // Insert new discount
        $sql = "INSERT INTO tbl_discount_buyxgety (p_id, buy, get, status, discount_statement) VALUES ($product_id, $buy, $get, $status, '$discount_statement')";
    } else {
        // Update existing discount
        $sql = "UPDATE tbl_discount_buyxgety SET buy=$buy, get=$get, status=$status, discount_statement='$discount_statement' WHERE id=$discount_id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
    echo "<script>reloadPage();</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Discounts</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        tr:hover {
            background-color: #e0e0e0;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: whitesmoke;
        }

        .disabled {
            background-color: #e0e0e0;
        }
    </style>
</head>

<body>

<?php include_once'navbar.php'; ?>

    <h1>Product Discounts</h1>
    <table>
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Buy Quantity</th>
            <th>Get Quantity</th>
            <th>Status</th>
            <th>Discount Statement</th>
            <th>Action</th>
        </tr>
        <?php
        // Database connection details
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

        // Fetch products
        $sql_products = "SELECT * FROM tbl_product";
        $result_products = $conn->query($sql_products);

        if ($result_products->num_rows > 0) {
            while ($row_product = $result_products->fetch_assoc()) {
                $product_id = $row_product['id'];
                $product_name = $row_product['product_title'];
                $price = $row_product['price'];

                // Fetch discount details
                $sql_discount = "SELECT * FROM tbl_discount_buyxgety WHERE p_id = $product_id";
                $result_discount = $conn->query($sql_discount);
                $buy = 0;
                $get = 0;
                $status = 0;
                $discount_statement = '';
                $discount_id = '';

                if ($result_discount->num_rows > 0) {
                    $row_discount = $result_discount->fetch_assoc();
                    $buy = $row_discount['buy'];
                    $get = $row_discount['get'];
                    $status = $row_discount['status'];
                    $discount_statement = $row_discount['discount_statement'];
                    $discount_id = $row_discount['id'];
                }

                $status_disabled = $status == 0 ? 'selected' : '';
                $status_enabled = $status == 1 ? 'selected' : '';
                // $row_class = $status == 0 ? 'class="disabled"' : '';

                echo "<tr>
                    <form action='' method='POST'>
                        <td>$product_id <input type='hidden' name='product_id' value='$product_id'></td>
                        <td>$product_name</td>
                        <td>$price</td>
                        <td><input type='number' name='buy' value='$buy' min=1></td>
                        <td><input type='number' name='get' value='$get' min=1></td>
                        <td>
                            <select name='status'>
                                <option value='0' $status_disabled>Disabled</option>
                                <option value='1' $status_enabled>Enabled</option>
                            </select>
                        </td>
                        <td><input type='text' name='discount_statement' value='$discount_statement'></td>
                        <td>
                            <button type='submit' name='discount_id' value='$discount_id'>Update</button>
                        </td>
                    </form>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No products found</td></tr>";
        }

        $conn->close();
        ?>
    </table>
</body>

</html>

<script>
    // Reload the page after form submission
    function reloadPage() {
        location.reload();
    }
</script>
