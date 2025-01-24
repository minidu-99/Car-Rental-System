<?php
// Database configuration
$host = 'localhost';      // Database host (usually localhost)
$username = 'root';       // Database username
$password = '';           // Database password (leave blank for default setups on localhost)
$database = 'carrental';  // Database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Optional: Uncomment for debugging
    //echo "Connected successfully to the database!";
}
?>
