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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Studențească | Inventar</title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico"> 
    <style>
        :root {
            font-size: 16px;
        }

        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #1a1a1a;
            line-height: 1.6;
        }
        
        #container {
            display: grid;
            grid-template-areas:
                "header"
                "nav"
                "main"
                "aside"
                "footer";
            gap: 0;
            min-height: 100vh;
        }

        header { grid-area: header; }
        nav { grid-area: nav; }
        main { grid-area: main; }
        aside { grid-area: aside; }
        footer { grid-area: footer; }

        header {
            background-color: #840909;
            color: white;
            padding: 1.25rem 0;
            text-align: center;
        }

        .header-image {
            width: 12.5rem;
            height: 12.5rem;
            display: block;
            margin: auto;
            border-radius: 50%;
            object-fit: cover;
        }
        
        header h1 {
            font-size: 2rem; 
            margin: 0.5rem 0;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            background-color: #333;
            margin: 0;
        }
        
        nav ul li {
            display: inline-block;
            margin: 0 0.5rem;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 0.625rem 0.9375rem;
            display: inline-block;
        }

        nav ul li a:hover {
            background-color: #555;
        }
        
        main {
            padding: 1.25rem;
            margin: 0 auto; 
            max-width: 95%;
            background-color: #545353; 
            border-radius: 0.5rem;
            box-shadow: none;
        }
        
        main h1 {
            color: #840909;
            text-align: center;
            font-size: 2rem;
        }
        
        table { 
            width: 100%;
            margin: 0.625rem auto 1.25rem auto; 
            border-collapse: collapse; 
            box-shadow: 0 0 0.625rem rgba(0, 0, 0, 0.05);
            background-color: white;
            color: #333;
            font-size: 0.875rem;
        }

        th, td { 
            border: 1px solid #ddd; 
            padding: 0.75rem; 
            text-align: left; 
        }

        th { 
            background-color: #007bff;
            color: white; 
            text-transform: uppercase;
        }
        
        tr:nth-child(even) { 
            background-color: #f2f2f2; 
        }

        .stoc-redus { background-color: #ffdddd; color: #a00; font-weight: bold; }

        footer {
            background-color: black;
            padding: 0.9375rem 0;
            text-align: center;
            color: white;
            margin-top: 0;
            font-size: 0.875rem;
        }

        #search-container {
            margin-bottom: 1.25rem;
            text-align: center;
        }
        
        #search-container input {
            padding: 0.625rem;
            width: 80%;
            max-width: 31.25rem;
            border: 1px solid #ccc;
            border-radius: 0.3125rem;
            font-size: 1rem;
        }
        
        img {
            max-width: 100%; 
            height: auto; 
            display: block;
        }
        
        .back-to-top {
            position: fixed;
            bottom: 1.25rem;
            right: 1.25rem;
            background-color: #333;
            color: white;
            text-decoration: none;
            padding: 0.625rem 1rem;
            border-radius: 0.3125rem;
            font-size: 1rem;
            opacity: 0.8;
            transition: opacity 0.3s;
            z-index: 1000;
        }

        .back-to-top:hover {
            opacity: 1;
        }
        
        #filter-box {
            background-color: rgb(153, 62, 62);
            padding: 1rem;
            border-radius: 0.5rem;
            color: white;
            margin: 1.25rem 0 1.25rem 1.25rem;
        }

        #filter-box h2 {
            margin-top: 0;
            color: white;
            border-bottom: 0.125rem solid #007bff;
            padding-bottom: 0.3125rem;
            font-size: 1.5rem;
        }

        #filter-box label {
            display: block;
            margin-bottom: 0.5rem;
        }

        @media (min-width: 56.25rem) { 
            #container {
                grid-template-columns: 1fr auto 20rem;
                grid-template-rows: auto auto 1fr auto;
                grid-template-areas:
                    "header header header"
                    "nav nav nav"
                    "main main aside" 
                    "footer footer footer";
                max-width: 75rem;
                margin: 0 auto;
            }

            main {
                grid-column: 1 / 3;
                max-width: 100%;
                padding-right: 1.25rem;
            }
            
            aside {
                grid-column: 3 / 4;
                padding: 0; 
                background-color: #545353;
            }
            
            nav ul li {
                margin: 0 1rem;
            }
            
            .col-Titlu { display: table-cell; }

            #filter-box {
                min-height: 16rem; 
                max-width: calc(20rem - 1.25rem); 
                margin: 1.25rem auto; 
            }
        }
        
        @media (max-width: 56.25rem) { 
            
            header h1 {
                font-size: 1.5rem; 
            }

            nav ul li {
                display: block;
                margin: 0;
            }
            
            nav ul li a {
                display: block;
                border-bottom: 1px solid #444;
            }
            
            nav ul li:last-child a {
                border-bottom: none;
            }

            main {
                padding: 1rem;
            }
            
            table, thead, tbody, th, td, tr {
                display: block;
            }
            
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr { 
                border: 1px solid #ccc; 
                margin-bottom: 0.625rem;
                border-radius: 0.5rem;
            }

            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }

            td:before {
                position: absolute;
                top: 0.75rem;
                left: 0.625rem;
                width: 45%;
                padding-right: 0.625rem;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #840909;
            }

            td:nth-of-type(1):before { content: "ID"; }
            td:nth-of-type(2):before { content: "Titlu"; }
            td:nth-of-type(3):before { content: "Autor"; }
            td:nth-of-type(4):before { content: "Gen"; }
            td:nth-of-type(5):before { content: "Format"; }
            td:nth-of-type(6):before { content: "An Publicare"; }
            td:nth-of-type(7):before { content: "Stoc Disponibil"; }
            
            .header-image {
                margin: 0 auto 10px auto;
            }
        }
    </style>
</head>
<body id="top">
    <div id="container">
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
        
        <main id="inventar">
            <h1>Inventar Cărți</h1>

            <div id="search-container">
                <input type="text" id="searchInput" placeholder="Căutați după Titlu, Autor sau Gen..." onkeyup="fetchBooks()">
            </div>

            <table>
                <thead>
                    <tr>
                        <th class="col-ID">ID</th>
                        <th class="col-Titlu">Titlu</th>
                        <th class="col-Autor">Autor</th>
                        <th class="col-Gen">Gen</th>
                        <th class="col-Format">Format</th>
                        <th class="col-AnPublicare">An Publicare</th>
                        <th class="col-Stoc">Stoc Disponibil</th>
                    </tr>
                </thead>
                <tbody id="bookTableBody">
                </tbody>
            </table>
        </main>
        
        <aside>
            <div id="filter-box">
                <h2>Afișare Coloane 🔎</h2>
                <form id="column-visibility-form">
                    <label>
                        <input type="checkbox" checked value="Titlu" onchange="updateTableVisibility()"> Titlu
                    </label>
                    <label>
                        <input type="checkbox" checked value="Autor" onchange="updateTableVisibility()"> Autor
                    </label>
                    <label>
                        <input type="checkbox" checked value="Gen" onchange="updateTableVisibility()"> Gen
                    </label>
                    <label>
                        <input type="checkbox" checked value="Format" onchange="updateTableVisibility()"> Format
                    </label>
                    <label>
                        <input type="checkbox" checked value="AnPublicare" onchange="updateTableVisibility()"> An Publicare
                    </label>
                    <label>
                        <input type="checkbox" checked value="Stoc" onchange="updateTableVisibility()"> Stoc Disponibil
                    </label>
                    <label>
                        <input type="checkbox" checked value="ID" onchange="updateTableVisibility()"> ID
                    </label>
                </form>
            </div>
        </aside>

        <footer>
            <p style="text-align: center; color:white;">&copy; 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
        </footer>
    </div>
    
    <a href="#top" class="back-to-top">⬆️ Mergi sus</a>
    
    <script>
        const columnMap = {
            'ID': 0,
            'Titlu': 1,
            'Autor': 2,
            'Gen': 3,
            'Format': 4,
            'AnPublicare': 5,
            'Stoc': 6
        };

        function updateTableVisibility() {
            const checkboxes = document.querySelectorAll('#column-visibility-form input[type="checkbox"]');
            const table = document.querySelector('table');
            
            checkboxes.forEach(checkbox => {
                const columnName = checkbox.value;
                const columnIndex = columnMap[columnName];
                const isChecked = checkbox.checked;

                if (columnIndex !== undefined) {
                    const th = table.querySelector(`.col-${columnName}`);
                    if (th) {
                        th.style.display = isChecked ? 'table-cell' : 'none';
                    }

                    document.querySelectorAll('#bookTableBody tr').forEach(row => {
                        const cell = row.children[columnIndex];
                        if (cell) {
                            cell.style.display = isChecked ? 'table-cell' : 'none';
                        }
                    });
                }
            });
        }
        
        function fetchBooks() {
            var searchTerm = document.getElementById('searchInput').value;
            var tableBody = document.getElementById('bookTableBody');

            var xhr = new XMLHttpRequest();
            
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    tableBody.innerHTML = this.responseText;
                    updateTableVisibility(); 
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