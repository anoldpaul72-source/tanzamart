<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders - TanzaMart</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f7f6;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }

        .header h2 {
            color: #333;
            font-size: 24px;
        }

        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .order-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border-top: 4px solid #00bcd4;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #eee;
        }

        .order-id {
            font-weight: bold;
            color: #00bcd4;
            font-size: 16px;
        }

        .order-date {
            font-size: 12px;
            color: #888;
        }

        .order-body .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #222;
            font-weight: 600;
            text-align: right;
        }

        .order-footer {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 14px;
            color: #555;
            font-weight: bold;
        }

        .total-amount {
            font-size: 18px;
            color: #2e7d32;
            font-weight: bold;
        }

        .no-orders {
            background: #fff;
            padding: 40px;
            text-align: center;
            border-radius: 8px;
            color: #777;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>📦 Orders List</h2>
    </div>

    <div class="orders-grid">
        <?php
        $result = $conn->query("SELECT * FROM orders ORDER BY id DESC");

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                ?>
                <div class="order-card">
                    <div class="order-header">
                        <span class="order-id">Order #<?php echo htmlspecialchars($row['id']); ?></span>
                        <span class="order-date"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                    </div>
                    
                    <div class="order-body">
                        <div class="info-row">
                            <span class="info-label">Customer:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['name']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['phone']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">City:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['city']); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Address:</span>
                            <span class="info-value"><?php echo htmlspecialchars($row['address']); ?></span>
                        </div>
                    </div>

                    <div class="order-footer">
                        <span class="total-label">Total Amount:</span>
                        <span class="total-amount">Tsh <?php echo number_format($row['total']); ?></span>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<div class='no-orders'>No orders found at the moment.</div>";
        }
        ?>
    </div>
</div>

</body>
</html>