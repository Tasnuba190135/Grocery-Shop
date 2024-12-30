<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centered Button</title>
    <style>
        .cont1 {
            display: flex;
            justify-content: center;
            margin-top: 20px; /* Adjust this value to position vertically */
        }
        .btn {
            text-decoration: none;
            padding: 20px 40px;
            font-size: medium;
            background-color: grey;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>

<div class="cont1">
    <a class="btn" href="home.php">Go to Home</a>
</div>

</body>

</html>
