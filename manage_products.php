<?php

include 'includes/db.php';

session_start();

if(!isset($_SESSION['email']) || $_SESSION['email'] != 'ashy@gmail.com'){
    header("Location:index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Products</title>

<style>
body{
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    padding: 20px;
}

h1{
    text-align: center;
    margin-bottom: 20px;
}

table{
    width: 100%;
    background: white;
    border-collapse: collapse;
    margin-top: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

table th, table td{
    border: 1px solid #ddd;
    padding: 12px;
    text-align: center;
}

table th{
    background: #111;
    color: white;
}

img{
    width: 80px;
    border-radius: 5px;
    height: 80px;
    object-fit: cover;
    background: #eee;
}

a{
    text-decoration: none;
    padding: 8px 12px;
    border-radius: 5px;
    color: white;
    display: inline-block;
}

.edit{
    background: orange;
    margin-right: 5px;
}

.delete{
    background: red;
}

.add-btn{
    background: #00bcd4;
    color: white;
    padding: 10px 15px;
    display: inline-block;
    margin-bottom: 20px;
}

.details-cell {
    max-width: 250px;
    text-align: left;
    font-size: 14px;
    color: #555;
}
</style>
</head>

<body>

<h1>Manage Products</h1>

<a class="add-btn" href="admin.php">Add Product</a>

<table>
<tr>
    <th>ID</th>
    <th>Image</th>
    <th>Name</th>
    <th>Price</th>
    <th>Details</th>
    <th>Action</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");

while($row = $result->fetch_assoc()){
    // Kutenganisha picha kwa koma na kuchukua ya kwanza tu kwa ajili ya admin preview
    $all_images = explode(',', $row['image']);
    
    // MAREKEBISHO: Kama picha ya kwanza haipo, weka picha ya default
    $main_image = !empty($all_images[0]) ? $all_images[0] : 'Images/default.jpg';
?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td>
        <img src="<?php echo htmlspecialchars($main_image); ?>" alt="Product Image">
    </td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td>Tsh <?php echo number_format($row['price']); ?></td>
    
    <td class="details-cell">
        <?php 
        $Details_text = isset($row['Details']) ? $row['Details'] : ''; 
        echo htmlspecialchars($Details_text); 
        ?>
    </td>
    
    <td>
        <a class="edit" href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a class="delete" href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
    </td>
</tr>
<?php } ?>
</table>

</body>
</html>