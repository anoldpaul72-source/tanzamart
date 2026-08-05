<?php
header('Content-Type: application/json');
include 'includes/db.php';

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if (empty($query)) {
    echo json_encode(['found' => false]);
    exit();
}

$lowerQuery = strtolower($query);

// 1. ZUIA MASWALI YA ORODHA / WINGI (General / List Intent Detection)
// Kama mteja anaulizia orodha, idadi, au bidhaa zote, tusirudishe bidhaa moja tu!
$listKeywords = ['list', 'products available', 'phones available', 'all products', 'all phones', 'how many', 'picha', 'orodha', 'ngapi zipo', 'bidhaa zote'];
foreach ($listKeywords as $keyword) {
    if (strpos($lowerQuery, $keyword) !== false) {
        echo json_encode(['found' => false]);
        exit();
    }
}

// 2. ORODHA YA MANENO YA KUONDOA (Stop Words)
$stopWords = [
    'bei gani', 'bei', 'inauzwa', 'inagharimu', 'kiasi gani', 'kiasi', 
    'gharama', 'ni shilingi', 'shingapi', 'shngapi', 'tsh', 'tshs', 
    'how much', 'price of', 'cost of', 'much is', 'how much is',
    'is', 'available', 'ipo', 'zipo', 'mna', 'mnao', 'naomba', 'about',
    'how about', 'do you have', 'have', 'there', 'any', 'give', 'the', 'of'
];

$cleanQuery = preg_replace('/[^\w\s]/u', '', $lowerQuery);

foreach ($stopWords as $word) {
    $cleanQuery = preg_replace('/\b' . preg_quote($word, '/') . '\b/ui', '', $cleanQuery);
}
$cleanQuery = trim(preg_replace('/\s+/', ' ', $cleanQuery));

if (empty($cleanQuery)) {
    echo json_encode(['found' => false]);
    exit();
}

// 3. VIPAUMBELE 1: Tafuta jina Kamili la bidhaa au sehemu ya jina kwenye `name`
$sql1 = "SELECT name, price FROM products WHERE LOWER(name) LIKE ? LIMIT 1";
$stmt1 = $conn->prepare($sql1);
if ($stmt1) {
    $term1 = "%" . $cleanQuery . "%";
    $stmt1->bind_param("s", $term1);
    $stmt1->execute();
    $res1 = $stmt1->get_result();

    if ($row1 = $res1->fetch_assoc()) {
        echo json_encode([
            'found' => true,
            'name' => $row1['name'],
            'price' => number_format($row1['price'])
        ]);
        $stmt1->close();
        $conn->close();
        exit();
    }
    $stmt1->close();
}

// 4. VIPAUMBELE 2: Kama haijapatikana kwenye Name, tafuta kwenye Details
$sql2 = "SELECT name, price FROM products WHERE LOWER(Details) LIKE ? LIMIT 1";
$stmt2 = $conn->prepare($sql2);
if ($stmt2) {
    $term2 = "%" . $cleanQuery . "%";
    $stmt2->bind_param("s", $term2);
    $stmt2->execute();
    $res2 = $stmt2->get_result();

    if ($row2 = $res2->fetch_assoc()) {
        echo json_encode([
            'found' => true,
            'name' => $row2['name'],
            'price' => number_format($row2['price'])
        ]);
        $stmt2->close();
        $conn->close();
        exit();
    }
    $stmt2->close();
}

// Kama haijapatikana kabisa
echo json_encode(['found' => false]);
$conn->close();
?>