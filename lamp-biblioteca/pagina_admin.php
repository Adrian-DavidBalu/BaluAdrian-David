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
    <title>Admin Dashboard | <?php echo htmlspecialchars($current_table); ?></title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico">
    <style>
        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0 0 50px 0;
            color: black;
        }
        header {
            background-color: #840909;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        .header-image {
            width: 100px;
            height: 100px;
            display: block;
            margin: 0 auto 10px auto;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
        }
        nav ul {
            list-style-type: none;
            padding: 0;
            text-align: center;
            background-color: #333;
            margin: 0;
            overflow-x: auto;
            white-space: nowrap;
        }
        nav ul li {
            display: inline-block;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
            transition: background-color 0.3s;
            font-weight: bold;
        }
        nav ul li a:hover, nav ul li a.active {
            background-color: #555;
        }
        section {
            padding: 20px;
            margin: 20px auto;
            max-width: 90%; 
            min-height: 400px;
            background-color: rgb(153, 62, 62);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            color: white;
            overflow-x: auto;
        }
        h1 {
            font-size: 1.5em;
            color: white;
            border-bottom: 2px solid #ffdd57;
            padding-bottom: 5px;
            margin-top: 0;
        }
        h2 {
             color: white;
             text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            color: #333;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
            word-wrap: break-word;
            max-width: 200px;
        }
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            font-size: 0.9em;
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
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
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
            padding: 15px;
            border: 1px solid #ffdd57;
            border-radius: 4px;
        }
        .intro-message {
            text-align: center;
            font-size: 1.2em;
            padding: 30px;
        }
        footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 15px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <header>
        <img src="poze/logo_biblioteca.jpg" alt="Logo Admin" class="header-image">
        <h1>Panou de Administrare 🛡️</h1>
        <p style="color: #ffdd57;">Vizualizare Bază de Date (Cont ROOT)</p>
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
                <a href="pagina_admin.php?action=logout" class="link-button" style="background-color: #dc3545; padding: 10px 15px;">Delogare Admin</a>
            </li>
        </ul>
    </nav>
    
    <section>
        <h1>Vizualizare Tabelă: <?php echo htmlspecialchars($current_table); ?></h1>
        <?php echo $table_content; ?>
    </section>

    <footer>
        <p>&copy; 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
    </footer>
</body>
</html>