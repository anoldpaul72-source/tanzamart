<?php
include 'includes/db.php';
session_start();

// Protection: Hakikisha ni Admin tu ndiye anayeweza kufuta
if(!isset($_SESSION['email']) || $_SESSION['email'] != 'ashy@gmail.com'){
    header("Location: index.php");
    exit();
}

if(isset($_GET['id'])){
    $cat_id = intval($_GET['id']);
    
    // 1. Set category_id kuwa NULL kwa bidhaa zote zilizokuwa kwenye category hii ili zisifutike
    $conn->query("UPDATE products SET category_id = NULL WHERE category_id = $cat_id");
    
    // 2. Futa category yenyewe
    $conn->query("DELETE FROM categories WHERE id = $cat_id");
}

// Rudisha Admin kwenye admin page
header("Location: admin.php");
exit();
?>