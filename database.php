<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "aura-streams";

// Create connections
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}
