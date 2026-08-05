<?php 
include 'includes/db.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

$result = $conn->query("SELECT * FROM products WHERE id = $id");

if ($result->num_rows == 0) {
    echo "<h2>Product not found!</h2><a href='products.php'>Go back to products</a>";
    exit();
}

$product = $result->fetch_assoc();
$product_images = explode(',', $product['image']);
$main_image = !empty($product_images[0]) ? $product_images[0] : 'Images/default.jpg';

// Ulinzi wa kusoma maelezo ya bidhaa (Details au details)
$details_text = isset($product['Details']) ? $product['Details'] : (isset($product['details']) ? $product['details'] : '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - TanzaMart</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.1); display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 40px; }
        .back-btn { display: inline-block; margin-bottom: 20px; color: #00bcd4; text-decoration: none; font-weight: bold; }
        .gallery { text-align: center; }
        
        /* MAREKEBISHO YA PICHA KUU: Dynamic height & contain ili isikatwe kamwe */
        .main-img { 
            width: 100%; 
            max-height: 400px; 
            height: auto; 
            object-fit: contain; 
            background: #fafafa; 
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); 
        }
        
        /* Style ya maandishi ya maelekezo ya rangi */
        .color-instruction { font-size: 14px; color: #666; font-weight: bold; margin-top: 15px; margin-bottom: 5px; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
        .color-instruction span { color: #00bcd4; }

        .thumbnails { display: flex; gap: 10px; margin-top: 10px; justify-content: center; flex-wrap: wrap; }
        
        /* MAREKEBISHO YA THUMBNAILS: object-fit contain pia kwa picha ndogo */
        .thumb { 
            width: 80px; 
            height: 80px; 
            object-fit: contain; 
            background: #fafafa; 
            border-radius: 5px; 
            cursor: pointer; 
            border: 2px solid #ddd; 
            transition: 0.2s; 
        }
        .thumb:hover, .thumb.active { border-color: #00bcd4; transform: scale(1.05); }
        .details-info h2 { margin-top: 0; font-size: 32px; color: #111; }
        .price { font-size: 24px; color: #00bcd4; font-weight: bold; margin: 15px 0; }
        .description { font-size: 16px; color: #555; line-height: 1.6; margin-bottom: 30px; border-top: 1px solid #eee; padding-top: 15px; }
        .cart-btn { background: #111; color: white; padding: 15px 30px; border: none; font-size: 18px; font-weight: bold; border-radius: 5px; cursor: pointer; transition: 0.3s; width: 100%; text-align: center; }
        .cart-btn:hover { background: #00bcd4; }
    </style>
</head>
<body>

<div style="max-width: 1000px; margin: 0 auto;">
    <a href="products.php" class="back-btn">← Back to All Products</a>
</div>

<div class="container">
    <div class="gallery">
        <img id="featured-image" src="<?php echo htmlspecialchars($main_image); ?>" alt="Product Image" class="main-img">
        
        <span class="color-instruction">🎨 <span>Choose Color</span> Then Add to Cart:</span>
        
        <div class="thumbnails">
            <?php 
            foreach($product_images as $key => $img_url) {
                if(!empty(trim($img_url))) {
                    $active_class = ($key === 0) ? 'active' : '';
                    ?>
                    <img src="<?php echo htmlspecialchars($img_url); ?>" class="thumb <?php echo $active_class; ?>" onclick="changeImage(this)">
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <div class="details-info">
        <h2><?php echo htmlspecialchars($product['name']); ?></h2>
        <div class="price">Tsh <?php echo number_format($product['price']); ?></div>
        
        <div class="description">
            <h4>Product Details:</h4>
            <p><?php echo nl2br(htmlspecialchars($details_text)); ?></p>
        </div>

        <form action="cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            
            <input type="hidden" id="selected-image" name="selected_image" value="<?php echo htmlspecialchars($main_image); ?>">
            
            <button type="submit" name="add_to_cart" class="cart-btn">🛒 Add to Cart</button>
        </form>
    </div>
</div>

<script>
function changeImage(element) {
    // 1. Badilisha picha kuu inayoonekana juu
    var newSrc = element.src;
    document.getElementById('featured-image').src = newSrc;
    
    // 2. Sasisha 'input' ya siri iliyopo kwenye fomu ili ibebe picha mpya
    document.getElementById('selected-image').value = newSrc;
    
    // 3. Weka border ya cyan kwenye picha iliyobonyezwa sasa hivi
    var thumbs = document.getElementsByClassName('thumb');
    for (var i = 0; i < thumbs.length; i++) {
        thumbs[i].classList.remove('active');
    }
    element.classList.add('active');
}
</script>

</body>
</html>