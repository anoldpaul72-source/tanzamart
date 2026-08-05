<?php
session_start();
include 'includes/db.php';

// Check if user is logged in. If not, redirect to login.php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user'];

// Fetch all orders for the logged-in user
$orders_query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC";
$orders_result = $conn->query($orders_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - TanzaMart</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f7f6;
            padding: 30px 20px;
            color: #333;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .header-bar h2 {
            font-size: 24px;
            color: #222;
        }

        .btn-home {
            text-decoration: none;
            background: #00bcd4;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
        }

        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-top: 4px solid #00bcd4;
        }

        .order-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .order-number {
            font-weight: bold;
            font-size: 18px;
            color: #00bcd4;
        }

        .order-date {
            font-size: 13px;
            color: #777;
        }

        .items-gallery {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .item-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fafafa;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #eee;
            min-width: 180px;
        }

        .item-thumbnail {
            width: 50px;
            height: 50px;
            border-radius: 6px;
            object-fit: cover;
        }

        .item-info {
            font-size: 13px;
        }

        .item-info strong {
            display: block;
            color: #333;
        }

        .order-details {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .total-price {
            font-size: 18px;
            font-weight: bold;
            color: #2e7d32;
        }

        .btn-receipt {
            background: #222;
            color: white;
            text-decoration: none;
            padding: 9px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-receipt:hover {
            background: #00bcd4;
        }

        .empty-orders {
            background: white;
            padding: 40px;
            text-align: center;
            border-radius: 12px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-bar">
        <h2>📦 My Orders</h2>
        <a href="index.php" class="btn-home">⬅️ Back to Shop</a>
    </div>

    <div class="orders-list">
        <?php if ($orders_result && $orders_result->num_rows > 0): ?>
            <?php while ($order = $orders_result->fetch_assoc()): ?>
                <?php 
                    $order_id = $order['id'];
                    
                    // Fetch all items belonging to this specific order
                    $items_sql = "SELECT oi.*, p.image FROM order_items oi 
                                  LEFT JOIN products p ON oi.product_id = p.id 
                                  WHERE oi.order_id = '$order_id'";
                    $items_result = $conn->query($items_sql);
                ?>
                <div class="order-card">
                    <div class="order-head">
                        <span class="order-number">Order #<?php echo $order_id; ?></span>
                        <span class="order-date">Date: <?php echo date('d M, Y', strtotime($order['created_at'] ?? 'now')); ?></span>
                    </div>

                    <!-- Purchased Items Gallery -->
                    <div class="items-gallery">
                        <?php if ($items_result && $items_result->num_rows > 0): ?>
                            <?php while ($item = $items_result->fetch_assoc()): ?>
                                <?php 
                                    $db_image = trim($item['image'] ?? '');
                                    $clean_image = ltrim($db_image, '/');

                                    if (!empty($clean_image) && file_exists($clean_image)) {
                                        $final_src = $clean_image;
                                    } elseif (!empty($clean_image) && file_exists("Images/" . basename($clean_image))) {
                                        $final_src = "Images/" . basename($clean_image);
                                    } elseif (!empty($clean_image) && file_exists("uploads/" . basename($clean_image))) {
                                        $final_src = "uploads/" . basename($clean_image);
                                    } else {
                                        $final_src = "https://via.placeholder.com/50?text=Item";
                                    }
                                ?>
                                <div class="item-box">
                                    <img src="<?php echo htmlspecialchars($final_src); ?>" alt="Product" class="item-thumbnail">
                                    <div class="item-info">
                                        <strong><?php echo htmlspecialchars($item['product_name']); ?></strong>
                                        <span>Qty: <?php echo $item['quantity']; ?></span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <span style="font-size: 13px; color: #999;">Item details unavailable</span>
                        <?php endif; ?>
                    </div>

                    <!-- Delivery & Payment Info -->
                    <div class="order-details">
                        <p>📍 <strong>Location:</strong> <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['address']); ?></p>
                        <?php if (!empty($order['transaction_id'])): ?>
                            <p>💳 <strong>Transaction Ref:</strong> <span style="color:#00bcd4;"><?php echo htmlspecialchars($order['transaction_id']); ?></span></p>
                        <?php endif; ?>
                    </div>

                    <div class="order-footer">
                        <div>
                            <span style="font-size: 12px; color: #777;">Total Paid:</span>
                            <div class="total-price">Tsh <?php echo number_format($order['total']); ?></div>
                        </div>
                        <a href="print_receipt.php?id=<?php echo $order_id; ?>" target="_blank" class="btn-receipt">
                            🖨️ Print Receipt
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-orders">
                <h3>You haven't placed any orders yet! 🛒</h3>
                <p style="margin-top: 10px;">Once you place an order, your order history will appear here.</p>
                <br>
                <a href="index.php" class="btn-home">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'support.php'; ?>
</body>
</html>