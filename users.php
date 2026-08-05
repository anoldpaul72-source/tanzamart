<?php
include 'includes/db.php';
// Ni vizuri kuwa na session_start ikiwa unataka kulinda page hii baadaye
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - TanzaMart</title>
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

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        /* Sehemu ya Juu ya Ukurasa */
        .header-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }

        h2 {
            color: #222;
            font-size: 26px;
            position: relative;
        }

        h2::after {
            content: '';
            display: block;
            width: 40px;
            height: 3px;
            background: #00bcd4;
            margin-top: 6px;
            border-radius: 2px;
        }

        .back-btn {
            text-decoration: none;
            color: #00bcd4;
            font-weight: bold;
            font-size: 14px;
            padding: 8px 16px;
            border: 2px solid #00bcd4;
            border-radius: 6px;
            transition: all 0.3s;
        }

        .back-btn:hover {
            background: #00bcd4;
            color: white;
        }

        /* Muonekano wa Jedwali (Table) */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f9f9f9;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        td {
            font-size: 15px;
            color: #444;
        }

        tr:hover {
            background-color: #fcfcfc;
        }

        /* ID Column maalum */
        td.id-cell {
            font-weight: bold;
            color: #888;
        }

        /* Kitufe cha kufuta (Delete Button) */
        .btn-delete {
            color: #e53935;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #ffebee;
            background: #fff8f8;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-delete:hover {
            background: #e53935;
            color: white;
            border-color: #e53935;
            box-shadow: 0 2px 5px rgba(229, 57, 53, 0.2);
        }

        /* Muonekano kwenye Simu */
        @media (max-width: 600px) {
            body { padding: 20px 10px; }
            .container { padding: 15px; }
            th, td { padding: 10px; font-size: 13px; }
            .header-area { flex-direction: column; gap: 15px; text-align: center; }
            h2::after { margin: 6px auto 0; }
            .back-btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

<div class="container">
    
    <div class="header-area">
        <h2>Registered Users</h2>
        <a href="manage_product.php" class="back-btn">← Back to Dashboard</a>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 40%;">Name</th>
                <th style="width: 35%;">Email</th>
                <th style="text-align: right; width: 15%;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $conn->query("SELECT * FROM users ORDER BY id DESC");

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                ?>
                <tr>
                    <td class="id-cell">#<?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td style="text-align: right;">
                        <a class="btn-delete" href="delete_user.php?id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Je, una uhakika unataka kumfuta mtumiaji huyu (<?php echo htmlspecialchars($row['name']); ?>)?');">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php 
                } 
            } else {
                echo "<tr><td colspan='4' style='text-align:center; color:#999; padding:30px;'>Hakuna watumiaji waliopatikana.</td></tr>";
            }
            ?>
        </tbody>
    </table>

</div>

</body>
</html>