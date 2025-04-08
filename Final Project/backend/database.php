<?php
// Database connection credentials
$host = "lqc353.encs.concordia.ca";
$username = "lqc353_4";
$password = "4oursom3";
$dbname = "lqc353_4";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>