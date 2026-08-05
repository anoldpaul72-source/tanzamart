<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// 1. KUPOKEA DATA KUTOKA KWENYE FOMU YA PRODUCT_DETAILS.PHP (POST)
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']);
    $selected_image = $_POST['selected_image']; // Link ya picha/rangi iliyochaguliwa

    // Kuchukua taarifa za bidhaa kutoka database
    $result = $conn->query("SELECT name, price FROM products WHERE id = $product_id");
    
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Tunatengeneza 'Key' ya kipekee kulingana na ID na picha (Rangi) 
        // Hii inasaidia simu moja ikiwekwa rangi mbili tofauti zikae kama mistari miwili tofauti
        $cart_key = $product_id . '_' . md5($selected_image);

        if (isset($_SESSION['cart'][$cart_key])) {
            // Kama bidhaa yenye rangi hii tayari ipo, tunaongeza idadi tu
            $_SESSION['cart'][$cart_key]['quantity'] += 1;
        } else {
            // Kama ni mpya, tunaiweka kwenye session pamoja na picha maalum aliyoichagua
            $_SESSION['cart'][$cart_key] = [
                'id' => $product_id,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $selected_image,
                'quantity' => 1
            ];
        }
    }
    
    header("Location: cart.php");
    exit();
}

// 2. KUONDOA BIDHAA KWENYE KAPU
if (isset($_GET['remove'])) {
    $cart_key = $_GET['remove'];
    if (isset($_SESSION['cart'][$cart_key])) {
        unset($_SESSION['cart'][$cart_key]);
    }
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - TanzaMart</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            padding: 40px 20px;
            color: #333;
        }

        .cart-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #222;
            font-size: 28px;
            position: relative;
        }

        h2::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: #00bcd4;
            margin: 8px auto 0;
            border-radius: 2px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        th {
            background-color: #f9f9f9;
            font-weight: 600;
            color: #666;
        }

        /* Mtindo wa picha ndogo ya bidhaa kwenye cart */
        .cart-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 15px;
            border: 1px solid #ddd;
        }

        .product-info {
            display: flex;
            align-items: center;
        }

        td.price {
            font-weight: bold;
            color: #444;
        }

        .btn-remove {
            color: #e53935;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background 0.2s;
        }

        .btn-remove:hover {
            background: #ffebee;
        }

        .cart-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 2px solid #eee;
        }

        .total-price {
            font-size: 22px;
            font-weight: bold;
            color: #00bcd4;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-continue {
            color: #555;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .btn-continue:hover {
            background: #eee;
        }

        .btn-checkout {
            background: #00bcd4;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: bold;
            box-shadow: 0 4px 10px rgba(0, 188, 212, 0.3);
            transition: all 0.2s;
        }

        .btn-checkout:hover {
            background: #0097a7;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 188, 212, 0.4);
        }

        .empty-cart {
            text-align: center;
            padding: 40px 0;
            color: #777;
        }

        .empty-cart a {
            display: inline-block;
            margin-top: 15px;
            color: #00bcd4;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="cart-container">
    <h2>Your Cart</h2>

    <?php if (empty($_SESSION['cart'])): ?>
        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="products.php">← Return to shopping</a>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $cart_key => $item) {
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td>
                            <div class="product-info">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product" class="cart-thumb">
                                <span><?php echo htmlspecialchars($item['name']); ?></span>
                            </div>
                        </td>
                        <td class="price">Tsh <?php echo number_format($item['price']); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td class="price">Tsh <?php echo number_format($subtotal); ?></td>
                        <td style="text-align: right;">
                            <a class="btn-remove" href="cart.php?remove=<?php echo $cart_key; ?>">Remove</a>
                        </td>
                    </tr>
                <?php 
                } 
                ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <span>Total:</span>
            <span class="total-price">Tsh <?php echo number_format($total); ?></span>
        </div>

        <div class="cart-actions">
            <a href="products.php" class="btn-continue">← Continue shopping</a>
            <a href="checkout.php" class="btn-checkout">Pay Now (Checkout)</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>