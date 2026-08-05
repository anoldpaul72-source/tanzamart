<?php

// Check Railway environment variables first, then standard DB_* env vars, then fallback to local defaults
$host     = getenv('MYSQLHOST')     ?: (getenv('DB_HOST') ?: "localhost");
$username = getenv('MYSQLUSER')     ?: (getenv('DB_USER') ?: "root");
$password = getenv('MYSQLPASSWORD') ?: (getenv('DB_PASS') ?: "");
$database = getenv('MYSQLDATABASE') ?: (getenv('DB_NAME') ?: "tanzamart");
$port     = getenv('MYSQLPORT')     ?: (getenv('DB_PORT') ?: 3306);

$conn = new mysqli($host, $username, $password, $database, (int)$port);

// Check connection
if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

?>