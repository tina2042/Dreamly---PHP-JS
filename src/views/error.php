<!-- error.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        h1 {
            color: #d9534f;
        }

        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Error <?php echo $errorCode; ?></h1>
    <p><?php echo $errorMessage; ?></p>
</body>
</html>
