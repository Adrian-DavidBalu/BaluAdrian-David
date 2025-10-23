<?php
include 'config.php'; 

if ($conn->connect_error) {
    die("Conexiune eșuată la baza de date: " . $conn->connect_error);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Studențească | Inventar</title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico"> 
    <style>
        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: black;
        }
        .header-image {
            width: 200px;
            height: 200px;
            display: block;
            margin: auto;
            border-radius: 50%;
            object-fit: cover;
        }
        header {
            background-color: #840909;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            background-color: #333;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
        }
        nav ul li a:hover {
            background-color: #555;
        }
        .main-content {
            padding: 20px;
            margin: 20px auto;
            max-width: 95%;
            background-color: #545353;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #840909;
            text-align: center;
        }
        table { 
            width: 100%;
            margin: 10px auto 20px auto; 
            border-collapse: collapse; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            background-color: white;
            color: #333;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 12px; 
            text-align: left; 
        }
        th { 
            background-color: #007bff;
            color: white; 
            text-transform: uppercase;
        }
        .stoc-redus { background-color: #ffdddd; color: #a00; font-weight: bold; }

        footer {
            background-color: black;
            padding: 15px 0;
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        #search-container {
            margin-bottom: 20px;
            text-align: center;
        }
        #search-container input {
            padding: 10px;
            width: 80%;
            max-width: 500px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <header>
        <img src="poze\logo_biblioteca.jpg" alt="logobiblioteca" class="header-image">
        <h1>SECRET:<span style="font-size: 0.7em;">Mergi ACASĂ și apasă pe logo!!!</span></h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Acasă</a></li>
            <li><a href="#inventar">Inventar Cărți</a></li>
            <li><a href="acces_interzis.php">Împrumuturi</a></li>
            <li><a href="acces_interzis.php">Membri</a></li>
        </ul>
    </nav>
    
    <div class="main-content" id="inventar">
        <h1>Inventar Cărți</h1>

        <div id="search-container">
            <input type="text" id="searchInput" placeholder="Căutați după Titlu, Autor sau Gen..." onkeyup="fetchBooks()">
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Autor</th>
                    <th>Gen</th>
                    <th>Format</th>
                    <th>An Publicare</th>
                    <th>Stoc Disponibil</th>
                </tr>
            </thead>
            <tbody id="bookTableBody">
            </tbody>
        </table>
    </div>

    <footer>
        <p style="text-align: center; color:white;">&copy; 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
    </footer>
    
    <script>
        function fetchBooks() {
            var searchTerm = document.getElementById('searchInput').value;
            var tableBody = document.getElementById('bookTableBody');

            var xhr = new XMLHttpRequest();
            
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    tableBody.innerHTML = this.responseText;
                }
            };
            
            xhr.open("GET", "cauta_carti.php?q=" + encodeURIComponent(searchTerm), true);
            xhr.send();
        }
        
        window.onload = function() {
            fetchBooks();
        };
    </script>
</body>
</html>