<?php
$server = "localhost";
$username = "root";
$password = ""; 
$database = "db_portospace"; // Your exact database name

// Establish the connection variable
$koneksidb = mysqli_connect($server, $username, $password, $database); 

// Verify the connection was successful
if (!$koneksidb) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>