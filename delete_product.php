<?php

include 'includes/db.php';

session_start();

if(!isset($_SESSION['email']) ||
$_SESSION['email'] != 'admin@gmail.com'){

    header("Location:index.php");

    exit();
}

$id = $_GET['id'];

$conn->query("DELETE FROM products WHERE id=$id");

header("Location:manage_products.php");

?>