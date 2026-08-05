<?php

include 'includes/db.php';

session_start();

// Protection to ensure only admin can access this page
if(!isset($_SESSION['email']) || $_SESSION['email'] != 'ashy@gmail.com'){
    header("Location: index.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: manage_products.php");
    exit();
}

$id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $result->fetch_assoc();

if(!$product){
    header("Location: manage_products.php");
    exit();
}

// Split the stored image string into an array of up to 4 elements
$existing_images = !empty($product['image']) ? explode(',', $product['image']) : [];

if(isset($_POST['update'])){

    $name = $conn->real_escape_string($_POST['name']);
    $price = $conn->real_escape_string($_POST['price']);
    $details = $conn->real_escape_string($_POST['details']); 
    $category_id = intval($_POST['category_id']);
    
    // 1. Gather all non-empty image fields from the array inputs
    $images_array = array_filter($_POST['images'], function($val) {
        return !empty(trim($val));
    });
    
    // 2. Convert them back into a single comma-separated string
    $image_string = $conn->real_escape_string(implode(',', $images_array));

    // 3. Update query (Inasafisha nguzo za image, category_id na details)
    $conn->query("UPDATE products SET
        name='$name',
        price='$price',
        category_id='$category_id',
        image='$image_string',
        details='$details' 
        WHERE id=$id");

    header("Location: manage_products.php");
    exit();
}

// Kuchukua details iwe imeandikwa kwa herufi kubwa au ndogo kwenye database
$product_details = isset($product['details']) ? $product['details'] : (isset($product['Details']) ? $product['Details'] : '');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - TanzaMart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 40px 20px;
        }
        .form-container {
            background: white;
            padding: 30px;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
            margin-top: 15px;
        }
        input[type="text"], input[type="number"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        textarea {
            height: 120px;
            resize: vertical;
        }
        .image-group {
            background: #fdfdfd;
            padding: 15px;
            border: 1px dashed #00bcd4;
            border-radius: 6px;
        }
        .image-group input {
            margin-bottom: 10px;
        }
        .image-group input:last-child {
            margin-bottom: 0;
        }
        button {
            width: 100%;
            background: #00bcd4;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 20px;
        }
        button:hover {
            background: #0097a7;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            text-decoration: none;
        }
        .back-link:hover {
            color: #111;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Product</h2>

    <form method="POST">

        <label for="name">Product Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

        <label for="price">Price (Tsh)</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>

        <label for="category_id">Product Category</label>
        <select name="category_id" id="category_id" required>
            <option value="">-- Select Category --</option>
            <?php 
            $cats = $conn->query("SELECT * FROM categories ORDER BY name ASC");
            if ($cats && $cats->num_rows > 0) {
                while($c = $cats->fetch_assoc()){
                    // Angalia kama category ndiyo hii iliyohifadhiwa kwenye bidhaa sasa hivi
                    $selected = ($product['category_id'] == $c['id']) ? 'selected' : '';
                    echo "<option value='".$c['id']."' $selected>".htmlspecialchars($c['name'])."</option>";
                }
            }
            ?>
        </select>

        <label>Product Images (Up to 4 Image Paths/URLs)</label>
        <div class="image-group">
            <input type="text" name="images[]" placeholder="Main Image URL (e.g. Images/photo.jpg)" value="<?php echo isset($existing_images[0]) ? htmlspecialchars($existing_images[0]) : ''; ?>" required>
            <input type="text" name="images[]" placeholder="Second Image URL (Optional)" value="<?php echo isset($existing_images[1]) ? htmlspecialchars($existing_images[1]) : ''; ?>">
            <input type="text" name="images[]" placeholder="Third Image URL (Optional)" value="<?php echo isset($existing_images[2]) ? htmlspecialchars($existing_images[2]) : ''; ?>">
            <input type="text" name="images[]" placeholder="Fourth Image URL (Optional)" value="<?php echo isset($existing_images[3]) ? htmlspecialchars($existing_images[3]) : ''; ?>">
        </div>

        <label for="details">Product Details</label>
        <textarea id="details" name="details" placeholder="Enter product details/description here..." required><?php echo htmlspecialchars($product_details); ?></textarea>

        <button type="submit" name="update">Update Product</button>
        
        <a href="manage_product.php" class="back-link">← Cancel & Go Back</a>
    </form>
</div>

</body>
</html>