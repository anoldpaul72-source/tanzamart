<?php
include 'includes/db.php';

session_start();

// Admin protection
if(!isset($_SESSION['email']) || $_SESSION['email'] != 'ashy@gmail.com'){
    header("Location:index.php");
    exit();
}

$msg = "";
$cat_msg = "";

// 1. KUONGEZA CATEGORY MPYA
if (isset($_POST['add_category'])) {
    $cat_name = trim($conn->real_escape_string($_POST['cat_name']));
    if (!empty($cat_name)) {
        $conn->query("INSERT INTO categories (name) VALUES ('$cat_name')");
        $cat_msg = "✅ Category '$cat_name' added successfully!";
    }
}

// 2. KUONGEZA BIDHAA MPYA
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $details = $_POST['details'];
    $price = $_POST['price'];
    $category_id = intval($_POST['category_id']);
    
    $images_array = array_filter($_POST['images']);
    $image_string = implode(',', $images_array);

    $conn->query("INSERT INTO products (name, price, image, Details, category_id) 
                  VALUES ('$name', '$price', '$image_string', '$details', '$category_id')");
                  
    $msg = "✅ Product added successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TanzaMart</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 40px 20px; color: #333; }
        .container { max-width: 850px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2, h3 { color: #111; margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-top: 15px; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="number"], select, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 15px; }
        textarea { height: 90px; resize: vertical; }
        .image-group { background: #fafafa; padding: 15px; border: 1px dashed #00bcd4; border-radius: 6px; margin-top: 5px; }
        .image-group input { margin-bottom: 10px; }
        .image-group input:last-child { margin-bottom: 0; }
        button { background: #00bcd4; color: white; border: none; padding: 12px 20px; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; transition: 0.3s; margin-top: 15px; width: 100%; }
        button:hover { background: #0097a7; }
        .success-msg { background: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-weight: bold; }
        .section-box { background: #fcfcfc; border: 1px solid #eee; padding: 20px; border-radius: 6px; margin-bottom: 30px; }
        .nav-btn { background: #111; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-bottom: 20px; font-size: 14px; }
        
        /* Style za Orodha ya Categories */
        .cat-list { margin-top: 20px; border-top: 1px solid #ddd; padding-top: 15px; }
        .cat-item { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 10px 15px; border: 1px solid #e0e0e0; border-radius: 4px; margin-bottom: 8px; }
        .delete-cat { color: red; text-decoration: none; font-weight: bold; font-size: 14px; }
        .delete-cat:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <a href="manage_product.php" class="nav-btn">← Manage All Products</a>
    <h2>Admin Dashboard</h2>

    <!-- SEHEMU YA 1: ADD CATEGORY & LIST CATEGORIES -->
    <div class="section-box">
        <h3>1. Manage Categories</h3>
        <?php if (!empty($cat_msg)) echo "<div class='success-msg'>$cat_msg</div>"; ?>
        
        <form method="POST">
            <label for="cat_name">Add New Category</label>
            <input type="text" id="cat_name" name="cat_name" placeholder="e.g. Phones, Electronics, Clothes" required>
            <button type="submit" name="add_category">Add Category</button>
        </form>

        <!-- Orodha ya Categories Zilizopo sasa hivi -->
        <div class="cat-list">
            <h4>Existing Categories:</h4>
            <?php
            $all_cats = $conn->query("SELECT * FROM categories ORDER BY name ASC");
            if ($all_cats && $all_cats->num_rows > 0) {
                while($cat = $all_cats->fetch_assoc()) {
                    echo "<div class='cat-item'>";
                    echo "<span>" . htmlspecialchars($cat['name']) . "</span>";
                    echo "<a class='delete-cat' href='delete_category.php?id=" . $cat['id'] . "' onclick='return confirm(\"Are you sure you want to delete this category?\");'>Delete</a>";
                    echo "</div>";
                }
            } else {
                echo "<p style='color:#777;'>No categories added yet.</p>";
            }
            ?>
        </div>
    </div>

    <!-- SEHEMU YA 2: ADD PRODUCT -->
    <div class="section-box">
        <h3>2. Add New Product</h3>
        <?php if (!empty($msg)) echo "<div class='success-msg'>$msg</div>"; ?>
        <form method="POST">
            <label for="name">Product Name</label>
            <input type="text" id="name" name="name" placeholder="Enter product name" required>

            <label for="price">Price (Tsh)</label>
            <input type="number" id="price" name="price" placeholder="Enter price" required>

            <label for="category_id">Select Category</label>
            <select name="category_id" id="category_id" required>
                <option value="">-- Choose Category --</option>
                <?php 
                $cats = $conn->query("SELECT * FROM categories ORDER BY name ASC");
                if ($cats && $cats->num_rows > 0) {
                    while($c = $cats->fetch_assoc()){
                        echo "<option value='".$c['id']."'>".htmlspecialchars($c['name'])."</option>";
                    }
                }
                ?>
            </select>

            <label>Product Images (Up to 4 URLs)</label>
            <div class="image-group">
                <input type="text" name="images[]" placeholder="Main Image URL (Required)" required>
                <input type="text" name="images[]" placeholder="Second Image URL (Optional)">
                <input type="text" name="images[]" placeholder="Third Image URL (Optional)">
                <input type="text" name="images[]" placeholder="Fourth Image URL (Optional)">
            </div>

            <label for="details">Product Details</label>
            <textarea id="details" name="details" placeholder="Enter product description..." required></textarea>
            
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </div>
</div>

</body>
</html>