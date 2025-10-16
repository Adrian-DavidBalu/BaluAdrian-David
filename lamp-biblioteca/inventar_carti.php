<?php
include 'config.php'; 

if ($conn->connect_error) {
    die("Conexiune eșuată la baza de date: " . $conn->connect_error);
}

$sql = "SELECT IDcarte, TitluCarte, AutorCarte, NrExemplare, TipFormat, Categorie, AnAparitie 
         FROM CARTE 
         ORDER BY TitluCarte ASC";

$result = $conn->query($sql);

if ($result === false) {
    if (isset($conn)) {
        $conn->close();
    }
    die("Eroare la interogarea bazei de date: " . $conn->error);
}
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
    </style>
</head>
<body>
    <header>
        <img src="poze\logo_biblioteca.jpg" alt="logobiblioteca" class="header-image">
        <h1>Biblioteca Studențească <span style="font-size: 0.7em;">— Inventar Cărți</span></h1>
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Acasă</a></li>
            <li><a href="#inventar">Inventar Cărți</a></li>
            <li><a href="#">Împrumuturi</a></li>
            <li><a href="#">Membri</a></li>
        </ul>
    </nav>
    
    <div class="main-content" id="inventar">
        <h1>Inventar Cărți</h1>

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
            <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $clasa_stoc = (($row["NrExemplare"] ?? 0) <= 5) ? 'stoc-redus' : '';
                    
                    echo "<tr class='$clasa_stoc'>";
                    echo "<td>" . htmlspecialchars($row["IDcarte"] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row["TitluCarte"] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row["AutorCarte"] ?? '') . "</td>";
                    echo "<td>" . htmlspecialchars($row["Categorie"] ?? '') . "</td>"; 
                    echo "<td>" . htmlspecialchars($row["TipFormat"] ?? '') . "</td>"; 
                    echo "<td>" . htmlspecialchars($row["AnAparitie"] ?? '') . "</td>"; 
                    echo "<td>" . htmlspecialchars($row["NrExemplare"] ?? '') . "</td>"; 
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' style='text-align: center; color: #777;'>
                          Nu au fost găsite cărți în baza de date. Vă rugăm să verificați dacă tabela CARTE conține date.
                      </td></tr>";
            }
            if (isset($result) && $result instanceof mysqli_result) {
                $result->free();
            }
            ?>
            </tbody>
        </table>
    </div>

<?php
if (isset($conn)) {
    $conn->close();
}
?>
    <footer>
        <p style="text-align: center; color:white;">&copy; 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
    </footer>
</body>
</html>
