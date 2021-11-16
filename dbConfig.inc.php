<?php

//database information
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "bimsdb";


// Create connection
$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    header('Location: connect-error.html');
    exit;
}
?>

