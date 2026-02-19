<?php
// InfinityFree database connection
// You can override any of these using environment variables:
// DB_HOST, DB_USER, DB_PASS, DB_NAME
$servername = getenv('DB_HOST') ?: "sql308.infinityfree.com";
$username = getenv('DB_USER') ?: "if0_41188596";
$password = getenv('DB_PASS') ?: "L2QC39aV6LRxD";
$database = getenv('DB_NAME') ?: "if0_41188596_aura_streams";

$db_connect_error = '';
$conn = @new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    $db_connect_error = $conn->connect_error;
} else {
    $conn->set_charset('utf8mb4');
}





