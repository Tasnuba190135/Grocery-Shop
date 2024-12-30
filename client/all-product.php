<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All product</title>
    <link rel="stylesheet" href="../css/all-product.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

    <style>
        .view-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: 2px solid transparent;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .view-button:hover {
            background-color: #45a049;
            border-color: #45a049;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- ---------navbar  -->
    <?php include_once 'navbar.php' ?>

    <div class="small-container">
        <div class="row row-2">
            <h2>All Product</h2>
        </div>
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

        // Fetch product data from the database
        $sql = "SELECT * FROM tbl_product";
        $result = $conn->query($sql);
        ?>

        <div class="row">
            <?php
            // Check if there are any products in the database
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='col-4'>";
                    echo "<img src='../db/image/" . $row["product_image_name"] . "' alt='product image'>";
                    echo "<h4>" . $row["product_title"] . "</h4>";
                    echo "<p>" . $row["price"] . " BDT </p>";
                    // echo '--------';
                    echo "<a class='view-button' href='single-product.php?id=" . $row["id"] . "'>View</a>";
                    echo "</div>";
                }
            } else {
                echo "No products available";
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- -------------footer -->
    <?php include_once 'footer.php' ?>


</body>

</html>