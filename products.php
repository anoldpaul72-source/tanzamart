<?php include 'includes/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - TanzaMart</title>

    <link rel="manifest" href="app_config.json">
    <meta name="theme-color" content="#00bcd4">

    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('sw.js')
            .then(reg => console.log('TanzaMart PWA Active!'))
            .catch(err => console.log('PWA Error: ', err));
        });
      }
    </script>

<style>
body{
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    margin: 0;
    padding: 20px;
}

.products-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto 20px auto;
}

.home-btn {
    text-decoration: none;
    color: #00bcd4;
    font-weight: bold;
    font-size: 16px;
    padding: 8px 15px;
    border: 2px solid #00bcd4;
    border-radius: 4px;
    transition: 0.3s;
}

.home-btn:hover {
    background: #00bcd4;
    color: white;
}

h2 {
    margin: 0;
    flex-grow: 1;
    text-align: center;
}

@media (max-width: 480px) {
    .products-header {
        flex-direction: column;
        gap: 15px;
    }
    h2 { order: 1; }
    .home-btn { order: 2; width: 100%; text-align: center; box-sizing: border-box; }
}

form{
    text-align: center;
    margin-bottom: 20px;
}

input{
    padding: 10px;
    width: 250px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button{
    padding: 10px 15px;
    background: #00bcd4;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
    font-weight: bold;
}

button:hover{
    background: #0097a7;
}

/* Category Filter Buttons Styling */
.category-filter {
    max-width: 1200px;
    margin: 0 auto 25px auto;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.category-btn {
    text-decoration: none;
    padding: 8px 18px;
    background: #white;
    background-color: #ffffff;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    transition: 0.3s;
}

.category-btn:hover, .category-btn.active {
    background: #00bcd4;
    color: white;
    border-color: #00bcd4;
}

.products{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.product{
    background: white;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: 0.3s;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product:hover{
    transform: translateY(-5px);
}

.product img{
    width: 100%;
    height: 220px;
    object-fit: contain;
    background: #fafafa;
    border-radius: 10px;
}

.product h3{
    margin: 15px 0 10px;
}

.product h3 a{
    text-decoration: none;
    color: #111;
    transition: 0.2s;
}

.product h3 a:hover{
    color: #00bcd4;
}

.product .product-details {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
    min-height: 40px;
}

.product p{
    color: #00bcd4;
    font-weight: bold;
    font-size: 18px;
    margin: 10px 0;
}

.product .cart-btn{
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;
    background: #111;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: 0.3s;
}

.product .cart-btn:hover{
    background: #00bcd4;
}
</style>
</head>

<body>

<div class="products-header">
    <a href="index.php" class="home-btn">← Back to Home</a>
    <h2>Our Products</h2>
    <div style="width: 130px;" class="desktop-spacer"></div> 
</div>

<form method="GET">
    <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <button type="submit">Search</button>
</form>

<!-- SEHEMU YA CATEGORIES -->
<div class="category-filter">
    <?php 
    $selected_cat = isset($_GET['category']) ? intval($_GET['category']) : 0;
    $all_active = ($selected_cat == 0 && !isset($_GET['search'])) ? 'active' : '';
    ?>
    <a href="products.php" class="category-btn <?php echo $all_active; ?>">All</a>
    
    <?php
    $cats_query = $conn->query("SELECT * FROM categories ORDER BY name ASC");
    if($cats_query && $cats_query->num_rows > 0) {
        while($cat = $cats_query->fetch_assoc()) {
            $active_class = ($selected_cat == $cat['id']) ? 'active' : '';
            echo '<a href="products.php?category='.$cat['id'].'" class="category-btn '.$active_class.'">'.htmlspecialchars($cat['name']).'</a>';
        }
    }
    ?>
</div>

<div class="products">

<?php
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' ORDER BY id DESC";
} elseif (isset($_GET['category']) && intval($_GET['category']) > 0) {
    $cat_id = intval($_GET['category']);
    $sql = "SELECT * FROM products WHERE category_id = $cat_id ORDER BY id DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY id DESC";
}

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $product_images = explode(',', $row['image']);
        $display_image = !empty($product_images[0]) ? $product_images[0] : 'Images/default.jpg';
?>

<div class="product">
    <a href="product_details.php?id=<?php echo $row['id']; ?>">
        <img src="<?php echo htmlspecialchars($display_image); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
    </a>

    <h3>
        <a href="product_details.php?id=<?php echo $row['id']; ?>">
            <?php echo htmlspecialchars($row['name']); ?>
        </a>
    </h3>
    
    <div class="product-details">
        <?php 
        $details_text = isset($row['Details']) ? $row['Details'] : (isset($row['details']) ? $row['details'] : ''); 
        
        if(strlen($details_text) > 80) {
            echo htmlspecialchars(substr($details_text, 0, 80)) . '...';
        } else {
            echo htmlspecialchars($details_text);
        }
        ?>
    </div>

    <p>Tsh <?php echo number_format($row['price']); ?></p>

    <a class="cart-btn" href="product_details.php?id=<?php echo $row['id']; ?>">
        Click Here To Get Details
    </a>
</div>

<?php 
    } 
} else {
    echo "<p style='text-align:center; grid-column: 1/-1; color: #777;'>No products found.</p>";
}
?>

</div>

</body>
</html>