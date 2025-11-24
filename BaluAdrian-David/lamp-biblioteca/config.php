<?php

$servername = "database"; 
$username = "root";       
$password = "password";  
$dbname = "biblioteca"; 
$port = 3306; 

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
