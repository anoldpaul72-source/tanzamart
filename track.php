<?php
session_start();
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Order - TanzaMart</title>

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
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .track-container {
            background: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
            text-transform: uppercase;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #0097a7;
        }

        .result-box {
            margin-top: 30px;
            padding: 20px;
            border-radius: 8px;
            background: #fafafa;
            border: 1px solid #eee;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        .status-pending { background: #fff3cd; color: #856404; }
        .status-shipping { background: #d1ecf1; color: #0c5460; }
        .status-completed { background: #d4edda; color: #155724; }

        .order-details {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="track-container">
    <h2>Track Your Order 📦</h2>
    
    <form method="POST">
        <div class="input-group">
            <label>Enter Payment Transaction ID</label>
            <input type="text" name="track_id" placeholder="e.g. RQA1234567" required>
        </div>
        <button type="submit" name="track">Track Status</button>
    </form>

    <?php
    if (isset($_POST['track'])) {
        $track_id = $conn->real_escape_string(trim($_POST['track_id']));
        $result = $conn->query("SELECT * FROM orders WHERE transaction_id='$track_id'");

        if ($result && $result->num_rows > 0) {
            $order = $result->fetch_assoc();
            $status = $order['status'] ? $order['status'] : 'Pending';

            $badge_class = 'status-pending';
            if ($status == 'In Transit' || $status == 'Ipo Njiani') {
                $badge_class = 'status-shipping';
            } elseif ($status == 'Delivered' || $status == 'Imekamilika') {
                $badge_class = 'status-completed';
            }
            ?>
            
            <div class="result-box">
                <h4>Order Status:</h4>
                <span class="status-badge <?php echo $badge_class; ?>">
                    <?php echo htmlspecialchars($status); ?>
                </span>

                <div class="order-details">
                    <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                    <p><strong>Destination City:</strong> <?php echo htmlspecialchars($order['city']); ?></p>
                    <p><strong>Total Paid:</strong> Tsh <?php echo number_format($order['total']); ?></p>
                </div>
            </div>

            <?php
        } else {
            echo "<div class='alert-error'>⚠️ Transaction ID not found. Please check your spelling and try again.</div>";
        }
    }
    ?>
</div>

</body>
</html>