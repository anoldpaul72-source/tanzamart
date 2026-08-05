<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit();
}

// Kupiga hesabu ya jumla kwa kutumia muundo mpya wa cart session
$total = 0;
foreach ($_SESSION['cart'] as $cart_key => $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment - TanzaMart</title>
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

        .checkout-container {
            max-width: 950px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 30px;
        }

        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }

        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        h3 {
            color: #222;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .total-box {
            background: #e0f7fa;
            border-left: 5px solid #00bcd4;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
        }

        .total-box h1 {
            color: #00bcd4;
            font-size: 28px;
        }

        .payment-method {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .mpesa { border-left: 5px solid #e53935; background: #fff8f8; }
        .tigopesa { border-left: 5px solid #00bcd4; background: #f0fbfc; }
        .halopesa { border-left: 5px solid #ffb300; background: #fffdf5; }

        .payment-method h4 {
            margin-bottom: 5px;
            font-size: 16px;
        }

        .payment-method p {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }

        .highlight {
            font-weight: bold;
            color: #111;
            background: #fff3cd;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-size: 14px;
            font-weight: 500;
        }

        .input-group input, .input-group textarea, .input-group select {
            width: 100%;
            padding: 11px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
        }

        .input-group textarea {
            height: 60px;
            resize: none;
        }

        .input-group input:focus, .input-group textarea:focus, .input-group select:focus {
            border-color: #00bcd4;
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.15);
        }

        .tx-input {
            border: 2px dashed #00bcd4 !important;
            background: #fafafa;
            font-weight: bold;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        button[name="pay"] {
            width: 100%;
            padding: 14px;
            background: #00bcd4;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button[name="pay"]:hover {
            background: #0097a7;
        }

        .success-box {
            margin-top: 20px;
            padding: 20px;
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            text-align: center;
        }

        .success-box p {
            color: #2e7d32;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .btn-whatsapp {
            display: inline-block;
            background: #25D366;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 6px;
            font-weight: bold;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    
    <div class="box">
        <h3>Step 1: Make Payment</h3>
        <div class="total-box">
            <p>Total Amount Payable</p>
            <h1>Tsh <?php echo number_format($total); ?></h1>
        </div>
        
        <p style="font-size: 14px; color:#666; margin-bottom: 15px;">Please send the above-mentioned amount to one of the following accounts:</p>

        <div class="payment-method mpesa">
            <h4>🔴 Vodacom M-Pesa</h4>
            <p>Send money to a phone number:<br>Number: <span class="highlight">0621 530 804</span> (Name: TANZAMART)</p>
        </div>

        <div class="payment-method tigopesa">
            <h4>🔵 Mixx By Yas</h4>
            <p>Pay via Merchant Number:<br>Reference number: <span class="highlight">998877</span> (Name: TANZAMART)</p>
        </div>

        <div class="payment-method halopesa">
            <h4>🟠 Halotel HaloPesa</h4>
            <p>Send money to a phone number:<br>Number: <span class="highlight">0621 530 804</span> (Name: TANZAMART)</p>
        </div>

        <p style="font-size: 13px; color: #e53935; font-weight: 500; margin-top: 15px;">
            ⚠️Once you have paid, copy the Transaction ID from your mobile network SMS, then paste/fill it in on the right side.
        </p>
    </div>

    <div class="box">
        <h3>Step 2: Fill Information</h3>
        
        <form method="POST">
            <div class="input-group">
                <label>Full name</label>
                <input type="text" name="name" placeholder="e.g: Juma Hamisi" required>
            </div>

            <div class="input-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="e.g: 0712345678" required>
            </div>

            <div class="input-group">
                <label>City</label>
                <input type="text" name="city" placeholder="e.g: Dar es Salaam" required>
            </div>

            <div class="input-group">
                <label>Address</label>
                <textarea name="address" placeholder="e.g: Kinondoni, vijana street" required></textarea>
            </div>

            <div class="input-group">
                <label>Network used to pay</label>
                <select name="payment_network" required>
                    <option value="">-- Select a network --</option>
                    <option value="M-Pesa">Vodacom M-Pesa</option>
                    <option value="Mixx By Yas">Mixx By Yas</option>
                    <option value="HaloPesa">HaloPesa</option>
                </select>
            </div>

            <div class="input-group">
                <label style="color: #00bcd4; font-weight: bold;">Transaction ID</label>
                <input type="text" name="transaction_id" class="tx-input" placeholder="e.g: RQA1234567" required>
            </div>

            <button type="submit" name="pay">Complete Your Order</button>
        </form>

        <?php
        if (isset($_POST['pay'])) {
            $name = $conn->real_escape_string($_POST['name']);
            $phone = $conn->real_escape_string($_POST['phone']);
            $city = $conn->real_escape_string($_POST['city']);
            $address = $conn->real_escape_string($_POST['address']);
            $network = $conn->real_escape_string($_POST['payment_network']);
            $tx_id = $conn->real_escape_string($_POST['transaction_id']);

            $total = 0;
            $message = "Hello TanzaMart,\n\n";
            $message .= "📦 *NEW ORDER SUBMITTED* 📦\n\n";
            
            foreach ($_SESSION['cart'] as $cart_key => $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
                
                $saved_image_path = $item['image']; // Inasoma mfano: Images/simu.jpg
                
                // MABADILIKO HAPA: Link sasa inaelekeza kwenye view_image.php ili isikatwe na InfinityFree
                $imageLink = "https://tanzamart.infinityfreeapp.com/view_image.php?img=" . urlencode($saved_image_path);
                
                $message .= "🔹 " . $item['name'] . " (x" . $item['quantity'] . ") - Tsh " . number_format($subtotal) . "\n"; 
                $message .= "🖼️ Rangi/Image Link: " . $imageLink . "\n\n";
            }
            
            $message .= "\n👤 *CUSTOMER DETAILS* 👤\n";
            $message .= "Name: " . $name . "\n";
            $message .= "Phone: " . $phone . "\n";
            $message .= "City: " . $city . "\n";
            $message .= "Address: " . $address . "\n\n";
            
            $message .= "💰 *PAYMENT INFO* 💰\n";
            $message .= "Network: " . $network . "\n";
            $message .= "Transaction ID: *" . $tx_id . "*\n";
            $message .= "Total Paid: Tsh " . number_format($total) . "\n";

            $encodedMessage = urlencode($message);
            $your_number = "255621530804";
            $url = "https://wa.me/" . $your_number . "?text=" . $encodedMessage;

            // SAVE ORDER KWENYE DATABASE
            $user_id = $_SESSION['user'];
            
            $conn->query("INSERT INTO orders (user_id, total, name, phone, address, city, transaction_id)
                          VALUES ('$user_id', '$total', '$name', '$phone', '$address', '$city', '$tx_id')");

            echo "<div class='success-box'>";
            echo "<p>✓ Your Order has been received!</p>";
            echo "<a href='$url' class='btn-whatsapp' target='_blank'>📲 Send Confirmation WhatsApp</a>";
            echo "</div>";
            
            // Futa vitu kwenye cart baada ya oda kukamilika
            $_SESSION['cart'] = [];
        }
        ?>
    </div>
</div>

</body>
</html>