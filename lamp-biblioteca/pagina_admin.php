<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: pagina_login.php");
    exit();
}

$available_tables = [
    'CARTE',
    'AUTOR',
    'MEMBRU',
    'TAXA',
    'ACTIUNE',
    'UTILIZATOR'
];

function getDbConnection($servername, $username, $password, $dbname, $port) {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Conexiune eșuată: " . $conn->connect_error);
    }
    return $conn;
}

function displayTableData($conn, $tableName) {
    $output = '';
    $sql = "SELECT * FROM " . $tableName;
    $result = $conn->query($sql);

    if ($result === false) {
        return "<p class='error-message'>Eroare la interogarea tabelei $tableName: " . $conn->error . "</p>";
    }

    if ($result->num_rows > 0) {
        $output .= "<table>";
        
        $output .= "<thead><tr>";
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            $output .= "<th>" . htmlspecialchars($field->name) . "</th>";
        }
        $output .= "</tr></thead>";

        $output .= "<tbody>";
        while ($row = $result->fetch_assoc()) {
            $output .= "<tr>";
            foreach ($row as $data) {
                $display_data = ($data === null) ? 'NULL' : htmlspecialchars($data);
                $output .= "<td>" . $display_data . "</td>";
            }
            $output .= "</tr>";
        }
        $output .= "</tbody>";
        $output .= "</table>";
    } else {
        $output .= "<p class='error-message'>Tabela '$tableName' nu conține înregistrări.</p>";
    }

    return $output;
}

$conn = getDbConnection($servername, $username, $password, $dbname, $port);
$table_content = '';
$current_table = '';

if (isset($_GET['table']) && in_array($_GET['table'], $available_tables)) {
    $current_table = $_GET['table'];
    $table_content = displayTableData($conn, $current_table);
} else {
    $table_content = "<p class='intro-message'>Selectați o tabelă din meniul de navigare pentru a vizualiza conținutul acesteia.</p>";
    $current_table = 'Dashboard';
}

if ($conn !== null) {
    $conn->close();
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: pagina_login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | <?php echo htmlspecialchars($current_table); ?></title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico">
    <style>
        :root {
            font-size: 16px;
        }

        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0 0 3.125rem 0;
            color: #1a1a1a;
            line-height: 1.5;
        }

        #container {
            display: grid;
            grid-template-areas:
                "header"
                "nav"
                "main"
                "footer";
            gap: 0;
            min-height: 100vh;
        }

        header { grid-area: header; }
        nav { grid-area: nav; }
        main { grid-area: main; }
        footer { grid-area: footer; }
        
        header {
            background-color: #840909;
            color: white;
            padding: 1.25rem 0;
            text-align: center;
        }

        .header-image {
            width: 6.25rem;
            height: 6.25rem;
            display: block;
            margin: 0 auto 0.625rem auto;
            border-radius: 50%;
            object-fit: cover;
            border: 0.1875rem solid white;
        }
        
        header h1 {
            font-size: 2rem;
            margin: 0.5rem 0 0 0;
        }
        
        header p {
            color: #ffdd57;
            font-size: 1rem;
            margin: 0.25rem 0;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            background-color: #333;
            margin: 0;
            overflow-x: auto;
            white-space: nowrap;
            display: flex;
            justify-content: center;
        }
        
        nav ul li {
            display: inline-block;
            flex-shrink: 0;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 0.625rem 0.9375rem;
            display: inline-block;
            transition: background-color 0.3s;
            font-weight: bold;
            font-size: 1rem;
        }
        
        nav ul li a:hover, nav ul li a.active {
            background-color: #555;
        }
        
        main {
            padding-bottom: 3.125rem;
        }

        section {
            padding: 1.25rem;
            margin: 1.25rem auto;
            max-width: 95%; 
            background-color: rgb(153, 62, 62);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
            color: white;
            overflow-x: auto;
        }
        
        section h1 {
            font-size: 1.875rem;
            color: white;
            border-bottom: 0.125rem solid #ffdd57;
            padding-bottom: 0.3125rem;
            margin-top: 0;
        }

        table {
            width: 100%;
            min-width: 40rem;
            border-collapse: collapse;
            margin-top: 1.25rem;
            background-color: white;
            color: #333;
            font-size: 0.875rem;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 0.5rem 0.75rem;
            text-align: left;
            word-wrap: break-word;
            max-width: 12.5rem;
        }
        
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        tr:hover {
            background-color: #ddd;
        }
        
        .link-button {
            background-color: #dc3545;
            color: white;
            padding: 0.3125rem 0.625rem;
            border: none;
            border-radius: 0.3125rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .link-button:hover {
            background-color: #c82333;
        }
        
        .error-message {
            color: #ffdd57;
            font-weight: bold;
            text-align: center;
            padding: 0.9375rem;
            border: 1px solid #ffdd57;
            border-radius: 0.25rem;
        }
        
        .intro-message {
            text-align: center;
            font-size: 1.2rem;
            padding: 1.875rem;
        }
        
        footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 0.9375rem 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -0.125rem 0.3125rem rgba(0, 0, 0, 0.2);
            z-index: 50;
        }

        .back-to-top {
            position: fixed;
            bottom: 4.375rem; 
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

        @media (max-width: 56.25rem) {
            
            header h1 {
                font-size: 1.5rem;
            }

            header p {
                font-size: 0.9rem;
            }
            
            nav ul {
                justify-content: flex-start;
            }
            
            nav ul li a {
                padding: 0.625rem 0.5rem;
                font-size: 0.9rem;
            }

            .link-button {
                padding: 0.3125rem 0.5rem;
                font-size: 0.9rem;
            }
            
            table {
                min-width: 35rem; 
            }
        }
    </style>
</head>
<body id="top">
    <div id="container">
        <header>
            <img src="poze/logo_biblioteca.jpg" alt="Logo Admin" class="header-image">
            <h1>Panou de Administrare 🛡️</h1>
            <p>Vizualizare Bază de Date (Cont ROOT)</p>
        </header>

        <nav>
            <ul>
                <?php foreach ($available_tables as $table): ?>
                    <li>
                        <a href="?table=<?php echo htmlspecialchars($table); ?>" 
                           class="<?php echo ($current_table === $table) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($table); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="pagina_admin.php?action=logout" class="link-button" style="background-color: #dc3545; padding: 0.625rem 0.9375rem;">Delogare Admin</a>
                </li>
            </ul>
        </nav>
        
        <main>
            <section>
                <h1>Vizualizare Tabelă: <?php echo htmlspecialchars($current_table); ?></h1>
                <?php echo $table_content; ?>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
        </footer>
    </div>
    
    <a href="#top" class="back-to-top">⬆️ Mergi sus</a>
</body>
</html>