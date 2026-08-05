<?php

include 'includes/db.php';

$id = $_GET['id'];

$conn->query("DELETE FROM orders WHERE id=$id");

header("Location: dashboard.php");

?>