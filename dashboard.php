<?php
include 'includes/db.php';
session_start();

// Dashboard Protection: Access allowed for admin only
if(!isset($_SESSION['email']) || $_SESSION['email'] != 'ashy@gmail.com'){
    header("Location: index.php");
    exit();
}

// PHP LOGIC TO UPDATE ORDER STATUS
if (isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $new_status = $conn->real_escape_string($_POST['new_status']);
    
    // Updates order status in the database
    $conn->query("UPDATE orders SET status='$new_status' WHERE id=$order_id");
    
    // Redirect with a success notification
    echo "<script>alert('Order status updated successfully!'); window.location.href='dashboard.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - TanzaMart</title>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body{
        font-family: 'Segoe UI', Arial, sans-serif;
        background:#f5f5f5;
        color: #333;
    }

    /* Sidebar Styling */
    .sidebar{
        width:240px;
        height:100vh;
        background:#111;
        position:fixed;
        padding-top:20px;
    }

    .sidebar h2{
        color:white;
        text-align:center;
        margin-bottom: 30px;
        letter-spacing: 1px;
    }

    .sidebar a{
        display:block;
        color:#ccc;
        padding:15px 20px;
        text-decoration:none;
        font-size: 15px;
        transition: 0.3s;
    }

    .sidebar a:hover{
        background:#00bcd4;
        color: white;
        padding-left: 25px;
    }

    /* Main Content Area Styling */
    .main{
        margin-left:240px;
        padding:30px;
    }

    .main h1 {
        margin-bottom: 25px;
        font-size: 28px;
        color: #222;
    }

    .main h2 {
        margin-bottom: 15px;
        font-size: 22px;
        color: #444;
    }

    /* Counter Cards */
    .cards{
        display:grid;
        grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
        gap:20px;
        margin-bottom:40px;
    }

    .card{
        background:white;
        padding:25px;
        border-radius:10px;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);
        text-align:center;
        border-bottom: 4px solid #ddd;
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .card:nth-child(1) { border-bottom-color: #00bcd4; }
    .card:nth-child(2) { border-bottom-color: #ffb300; }
    .card:nth-child(3) { border-bottom-color: #2c3e50; }

    .card h1{
        color:#222;
        font-size: 36px;
        margin-bottom: 5px;
    }

    .card p{
        color: #777;
        font-size: 14px;
        font-weight: 500;
        text-transform: uppercase;
    }

    /* Orders Table Styling */
    .table-container {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    table{
        width:100%;
        border-collapse:collapse;
    }

    table th, table td{
        padding:15px;
        text-align:left;
        font-size: 14px;
    }

    table th{
        background:#111;
        color:white;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
    }

    table tr:nth-child(even) {
        background: #f9f9f9;
    }

    table tr:hover {
        background: #f1f1f1;
    }

    table td{
        border-bottom:1px solid #eee;
        vertical-align: middle;
    }

    /* Current Status Badge Styling */
    .status-text {
        font-weight: bold;
        font-size: 12px;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 5px;
        text-transform: uppercase;
    }
    .status-pending { background: #fff3cd; color: #856404; }
    .status-shipping { background: #d1ecf1; color: #0c5460; }
    .status-completed { background: #d4edda; color: #155724; }

    /* Dropdown and Status Form Options */
    .status-form select {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 13px;
        outline: none;
    }

    .status-form button {
        padding: 6px 12px;
        background: #00bcd4;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
        transition: 0.2s;
    }

    .status-form button:hover {
        background: #0097a7;
    }

    /* Action Button: Delete */
    .btn-delete {
        color: #c62828;
        text-decoration: none;
        font-weight: bold;
        font-size: 13px;
        border: 1px solid #ffcdd2;
        padding: 5px 10px;
        border-radius: 4px;
        background: #ffebee;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background: #c62828;
        color: white;
        border-color: #c62828;
    }

    .tx-id {
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        color: #555;
        background: #eee;
        padding: 2px 6px;
        border-radius: 4px;
    }
</style>
</head>

<body>

<div class="sidebar">
    <h2>TanzaMart</h2>
    <a href="dashboard.php" style="background: #222; color: #00bcd4;">Dashboard</a>
    <a href="products.php">Products</a>
    <a href="orders.php">Orders</a>
    <a href="manage_products.php">Manage Products</a>
    <a href="users.php">Users</a>
    <a href="logout.php" style="color: #ff8a80;">Logout</a>
</div>

<div class="main">

    <h1>Admin Dashboard</h1>

    <div class="cards">
        <?php
        $product = $conn->query("SELECT * FROM products");
        $totalProducts = $product->num_rows;

        $order = $conn->query("SELECT * FROM orders");
        $totalOrders = $order->num_rows;

        $user = $conn->query("SELECT * FROM users");
        $totalUsers = $user->num_rows;
        ?>

        <div class="card">
            <h1><?php echo $totalProducts; ?></h1>
            <p>Total Products</p>
        </div>

        <div class="card">
            <h1><?php echo $totalOrders; ?></h1>
            <p>Total Orders</p>
        </div>

        <div class="card">
            <h1><?php echo $totalUsers; ?></h1>
            <p>Total Users</p>
        </div>
    </div>

    <h2>Recent Orders</h2>

    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>City</th>
                <th>Transaction ID</th>
                <th>Total</th>
                <th>Order Status & Action</th>
                <th>Delete</th>
            </tr>

            <?php
            $result = $conn->query("SELECT * FROM orders ORDER BY id DESC");

            while($row = $result->fetch_assoc()){
                // Set default to Pending if empty
                $current_status = isset($row['status']) && $row['status'] != '' ? $row['status'] : 'Pending';
                
                // Track dynamic CSS dynamic class names matching the language system
                $badge_class = 'status-pending';
                if ($current_status == 'In Transit' || $current_status == 'Ipo Njiani') {
                    $badge_class = 'status-shipping';
                } elseif ($current_status == 'Delivered' || $current_status == 'Imekamilika') {
                    $badge_class = 'status-completed';
                }
                
                $tx_id = isset($row['transaction_id']) && $row['transaction_id'] != '' ? $row['transaction_id'] : '---';
            ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['city']); ?></td>
                <td><span class="tx-id"><?php echo htmlspecialchars($tx_id); ?></span></td>
                <td><strong>Tsh <?php echo number_format($row['total']); ?></strong></td>
                
                <td>
                    <div class="status-text <?php echo $badge_class; ?>">
                        <?php echo $current_status; ?>
                    </div>
                    <form method="POST" class="status-form">
                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                        <select name="new_status" required>
                            <option value="">-- Change Status --</option>
                            <option value="Pending">Pending</option>
                            <option value="In Transit">In Transit</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                        <button type="submit" name="update_status">Update</button>
                    </form>
                </td>
                
                <td>
                    <a href="delete_order.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this order record?');">
                        Delete
                    </a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</div>

</body>
</html>