<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online grocery</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css">
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">

</head>

<body>
    <!-- ---------navbar  -->
    <div class="container">
        <div class="navbar">
            <div class="logo">
                <img src="../images/logo.png" alt="" width="125">
            </div>
            <nav>
                <ul id="menuitem">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="client/all-product.php">Product</a></li>
                    <li><a href="client/about.html" target="_blank">About</a></li>
                    <li><a href="client/contact.html" target="_blank">Contact</a></li>
                    <li><a href="client/account.php">Account</a></li>
                </ul>
            </nav>
            <a href="client/carts.php">
                <img src="images/cart.png" alt="" width="30px" height="30px">
            </a>
            </div>
    </div>

    <div class="header">
            <div class="row">
                <div class="col-2" style="margin: 20px 0px;">
                    <h2> Grocery Delivered at your Doorstep </h2>
                    <p>grocery.com is an online shop available in Dhaka, Jashore and Chattogram. We <br>believe the time is valuable to our fellow Dhaka residents, and that they should <br>not have to waste hours in traffic, brave harsh weather, and wait in line just to buy necessities like eggs! </p>
                    <a href="client/all-product.php" class="btn"> Explore Now &#8594; </a>
                </div>
                <div class="col-2">
                    <img src="images/imageBanner.png" alt="">
                </div>
            </div>
        </div>
    </div>




    <!-- footer-->
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col-1">
                    <h3> Downlod Our App</h3>
                    <p> Downlod our android and ios mobile phone</p>
                    <div class="app-logo">
                        <img src="images/play-store.png" alt="">
                        <img src="images/app-store.png" alt="">
                    </div>
                </div>
                <div class="footer-col-2">
                    <img src="images/logo.png" alt="">
                    <p> Font Awesome CDN is the Awesome on your website or app,
                    </p>
                </div>
                <div class="footer-col-3">
                    <h3> Downlod Our App</h3>
                    <ul>
                        <li> Coupons</li>
                        <li> Blog</li>
                        <li> Return Policy</li>
                        <li> Join Affiliate</li>
                    </ul>
                </div>
                <div class="footer-col-4">
                    <h3> Follw us</h3>
                    <ul>
                        <li> FaceBook</li>
                        <li> Instagram</li>
                        <li> Tweiter</li>
                        <li> lite</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!--js-->
    <script>
        var menuitem = document.getElementById("menuitem")
        menuitem.style.maxHeight = "0px"

        function menutoggle() {
            if (menuitem.style.maxHeight == "0px") {
                menuitem.style.maxHeight = "200px";
            } else {
                menuitem.style.maxHeight = "0px"
            }
        }
    </script>


</body>

</html>