<?php
session_start();
include 'includes/db.php';

if (!isset($_GET['id'])) {
    die("Order ID is missing.");
}

$order_id = (int)$_GET['id'];

// Chukua taarifa za Oda
$order_sql = "SELECT * FROM orders WHERE id = '$order_id'";
$order_res = $conn->query($order_sql);

if (!$order_res || $order_res->num_rows == 0) {
    die("Order not found.");
}

$order = $order_res->fetch_assoc();

// Chukua bidhaa zilizopo kwenye hii Oda
$items_sql = "SELECT oi.*, p.name FROM order_items oi 
              LEFT JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = '$order_id'";
$items_res = $conn->query($items_sql);
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #<?php echo $order_id; ?> - TanzaMart</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', Courier, monospace; /* Font ya muundo wa risiti */
        }

        body {
            background: #f0f0f0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .receipt-card {
            background: #fff;
            width: 100%;
            max-width: 400px;
            padding: 25px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .store-header {
            text-align: center;
            border-bottom: 2px dashed #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .store-header h2 {
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .store-header p {
            font-size: 12px;
            color: #555;
        }

        .info-section {
            font-size: 13px;
            margin-bottom: 15px;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .items-table td {
            padding: 6px 0;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            border-top: 2px dashed #333;
            padding-top: 10px;
            font-size: 15px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .footer-note {
            text-align: center;
            font-size: 11px;
            color: #777;
            margin-top: 15px;
        }

        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-print {
            background: #00bcd4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            font-family: Arial, sans-serif;
        }

        /* Ficha button wakati wa ku-print */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt-card {
                box-shadow: none;
                width: 100%;
                max-width: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Print Receipt / Save PDF</button>
    </div>

    <div class="receipt-card">
        <div class="store-header">
            <h2>TanzaMart</h2>
            <p>Online Marketplace</p>
            <p>Phone: 0621 530 804</p>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span>Receipt No:</span>
                <strong>#<?php echo $order_id; ?></strong>
            </div>
            <div class="info-row">
                <span>Date:</span>
                <span><?php echo date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now')); ?></span>
            </div>
            <div class="info-row">
                <span>Customer:</span>
                <span><?php echo htmlspecialchars($order['name']); ?></span>
            </div>
            <div class="info-row">
                <span>Phone:</span>
                <span><?php echo htmlspecialchars($order['phone']); ?></span>
            </div>
            <div class="info-row">
                <span>City:</span>
                <span><?php echo htmlspecialchars($order['city']); ?></span>
            </div>
            <div class="info-row">
                <span>Txn ID:</span>
                <span><?php echo htmlspecialchars($order['transaction_id']); ?></span>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items_res && $items_res->num_rows > 0): ?>
                    <?php while ($item = $items_res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name'] ?? 'Product'); ?></td>
                            <td class="text-right"><?php echo $item['quantity']; ?></td>
                            <td class="text-right"><?php echo number_format($item['price'] * $item['quantity']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="total-section">
            <span>TOTAL PAID:</span>
            <span>Tsh <?php echo number_format($order['total']); ?></span>
        </div>

        <div class="footer-note">
            <p>Thank you for shopping with TanzaMart!</p>
            <p>*** Paid Order ***</p>
        </div>
    </div>
</div>

</body>
</html>