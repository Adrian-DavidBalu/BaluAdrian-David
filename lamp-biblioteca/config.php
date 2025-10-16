<?php
// Numele serviciului din docker-compose.yml.
// Datorită Docker Compose, 'database' se rezolvă la adresa IP internă corectă.
$servername = "database"; 
$username = "root";       // Utilizatorul setat în docker-compose.yml
$password = "password";   // Parola setată în docker-compose.yml
$dbname = "biblioteca"; 
$port = 3306;             // Portul standard MariaDB (intern)

// Crearea conexiunii
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificarea conexiunii
if ($conn->connect_error) {
    // Dacă conexiunea eșuează, se va afișa o eroare clară
    die("Conexiune eșuată: " . $conn->connect_error);
}

// Setează setul de caractere pentru a gestiona caracterele speciale
$conn->set_charset("utf8mb4");
?>
