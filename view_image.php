<?php
if (!isset($_GET['img']) || empty($_GET['img'])) {
    echo "No image selected.";
    exit();
}
$img = htmlspecialchars($_GET['img']);
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TanzaMart - Product Image</title>
    <style>
        body {
            background-color: #111;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        img {
            max-width: 100%;
            max-height: 100vh;
            object-fit: contain;
            box-shadow: 0 0 20px rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <img src="<?php echo $img; ?>" alt="Product Color Selected">
</body>
</html>