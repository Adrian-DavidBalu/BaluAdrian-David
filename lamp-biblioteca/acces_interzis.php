<?php
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acces Interzis | Biblioteca Studențească</title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico"> 
    <style>
        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: black;
            display: flex;
            flex-direction: column;
            min-height: 100vh; 
        }

        header {
            background-color: #840909;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .header-image {
            width: 200px;
            height: 200px;
            display: block;
            margin: auto;
            border-radius: 50%;
            object-fit: cover;
        }

        h1 {
            margin-top: 15px;
            font-size: 2.2em;
        }

        h1 span {
            font-size: 0.7em;
            display: block;
            margin-top: 5px;
        }
        
        nav {
            display: none;
        }
        
        .main-content {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .error-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            max-width: 450px;
            width: 100%;
            text-align: center;
            border-top: 5px solid #840909;
        }
        
        .error-icon {
            width: 250px;
            height: 250px;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .error-container a { 
            display: block;
            margin-bottom: 20px;
        }
        
        h2 {
            color: #840909;
            margin-bottom: 25px;
            font-size: 1.6em;
        }

        .button-group a {
            display: block;
            margin: 10px 0;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.05em;
            transition: background-color 0.3s ease, transform 0.2s ease;
            font-weight: bold;
        }
        
        .btn-login {
            background-color: #007bff;
            color: white;
        }
        
        .btn-login:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        .btn-home {
            background-color: #333;
            color: white;
        }
        
        .btn-home:hover {
            background-color: #555;
            transform: translateY(-2px);
        }

        footer {
            background-color: black;
            padding: 15px 0;
            text-align: center;
            color: white;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <img src="poze/logo_biblioteca.jpg" alt="Logo Biblioteca" class="header-image">
        <h1>Biblioteca Studențească <span>— Acces Restricționat —</span></h1>
    </header>

    <div class="main-content">
        <div class="error-container">
            <a href="temnita.php">
                <img src="poze/carte_hot.jpg" alt="Eroare Acces - Cartea Furată" class="error-icon"> 
            </a>
            
            <h2>Doar ADMINISTRATORUL are acces la aceste date!</h2>

            <div class="button-group">
                <a href="pagina_login.php" class="btn-login">MERGI LA PAGINA DE LOGIN</a>
                
                <a href="index.php" class="btn-home">SPRE INTRAREA ÎN BIBLIOTECĂ</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
    </footer>
</body>
</html>