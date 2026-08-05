<?php
include 'includes/db.php';

// Read Gemini API Key from environment variable or set your key here
$apiKey = getenv('GEMINI_API_KEY') ?: "";

if (isset($_POST['message'])) {
    $userMsg = trim($_POST['message']);

    if (empty($userMsg)) {
        echo "Please enter a message.";
        exit();
    }

    // 1. FETCH PRODUCT LIST FROM DATABASE FOR AI CONTEXT
    $productsList = "";
    $sql = "SELECT product_name, price FROM products LIMIT 20";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productsList .= "- " . $row['product_name'] . " (Tsh " . number_format($row['price']) . ")\n";
        }
    } else {
        $productsList = "Currently, no products are listed in the store.";
    }

    // 2. BUILD SYSTEM INSTRUCTIONS / CONTEXT FOR THE AI
    $systemPrompt = "You are an official Customer Support AI Assistant for TanzaMart, an e-commerce online marketplace.\n"
        . "Your goal is to answer customers politely, accurately, and concisely in English.\n\n"
        . "Key Store Information:\n"
        . "- Payment options: Vodacom M-Pesa (0621 530 804), Mixx By Yas (998877), and HaloPesa (0621 530 804).\n"
        . "- Ordering process: Add products to Cart -> Go to Checkout -> Fill in Transaction ID -> Click Complete Order -> Send order confirmation via WhatsApp.\n"
        . "- Delivery: We deliver products nationwide across all regions in Tanzania.\n\n"
        . "Current Product Inventory in Store:\n"
        . $productsList . "\n\n"
        . "If a customer asks about a product that is not listed above, inform them politely that it is currently out of stock or recommend searching via the site search bar.\n"
        . "Answer the following user question concisely:";

    // 3. PREPARE PAYLOAD FOR GEMINI API
    $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

    $payload = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $systemPrompt . "\n\nUser Question: " . $userMsg]
                ]
            ]
        ]
    ];

    // 4. SEND CURL REQUEST TO GOOGLE GEMINI API
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // 5. PROCESS API RESPONSE
    if ($httpCode === 200 && $response) {
        $responseData = json_decode($response, true);
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $aiReply = $responseData['candidates'][0]['content']['parts'][0]['text'];
            echo trim($aiReply);
            exit();
        }
    }

    // Fallback message if API fails
    echo "Sorry, I am experiencing network issues at the moment. How can I assist you regarding payments or our available products?";
}
?>