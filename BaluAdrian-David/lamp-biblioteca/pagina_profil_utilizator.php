<?php
require_once 'config.php';
session_start();
if (!isset($_SESSION['IDmembru'])) {
    header("Location: pagina_login.php");
    exit();
}

$id_membru = $_SESSION['IDmembru'];

function getDbConnection($servername, $username, $password, $dbname, $port) {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        error_log("Conexiune la baza de date eșuată: " . $conn->connect_error);
        return null;
    }
    return $conn;
}

$conn = getDbConnection($servername, $username, $password, $dbname, $port);
if ($conn === null) {
    die("Eroare la conexiunea cu baza de date.");
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'rezerva_carte') {
    $id_carte_rezervare = filter_input(INPUT_POST, 'IDcarte', FILTER_VALIDATE_INT);
    $taxa_rezervare = 5;

    if ($id_carte_rezervare) {
        $conn->begin_transaction();
        
        try {
            $sql_check = "SELECT TitluCarte FROM CARTE WHERE IDcarte = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("i", $id_carte_rezervare);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();
            $book = $result_check->fetch_assoc();
            $stmt_check->close();

            if (!$book) {
                throw new Exception("ID carte invalid sau cartea nu există.");
            }

            $data_actiune = date('Y-m-d H:i:s');
            // VALOAREA CORECTĂ PENTRU TipActiune
            $tip_actiune = 'Rezervare'; 
            // VALOAREA CORECTĂ PENTRU StatusCarteActiune (Asumă că a fost adăugată în ENUM)
            $status_actiune = 'Rezervată'; 
            
            $sql_actiune = "INSERT INTO ACTIUNE (IDmembru, IDcarte, TipActiune, StatusCarteActiune, DataActiune) 
                            VALUES (?, ?, ?, ?, ?)";
            $stmt_actiune = $conn->prepare($sql_actiune);
            // Tipul de legare este iisss
            $stmt_actiune->bind_param("iisss", $id_membru, $id_carte_rezervare, $tip_actiune, $status_actiune, $data_actiune);
            
            if (!$stmt_actiune->execute()) {
                throw new Exception("Eroare la inserarea acțiunii: " . $stmt_actiune->error);
            }
            $id_actiune = $conn->insert_id;
            $stmt_actiune->close();

            $sql_rezervare = "INSERT INTO REZERVARE (IDactiune, TaxaRezervare) VALUES (?, ?)";
            $stmt_rezervare = $conn->prepare($sql_rezervare);
            $stmt_rezervare->bind_param("ii", $id_actiune, $taxa_rezervare);
            
            if (!$stmt_rezervare->execute()) {
                throw new Exception("Eroare la inserarea rezervării: " . $stmt_rezervare->error);
            }
            $stmt_rezervare->close();

            $conn->commit();
            $message = "🎉 Cartea **" . htmlspecialchars($book['TitluCarte']) . "** a fost rezervată! Data de expirare este " . formatDateTime(date('Y-m-d H:i:s', strtotime('+48 hours'))) . ".";

        } catch (Exception $e) {
            $conn->rollback();
            $message = "Rezervarea a eșuat. Vă rugăm verificați ID-ul cărții. Eroare tehnică: " . $e->getMessage();
        }

    } else {
        $message = "ID carte invalid.";
    }
    
    $_SESSION['message'] = $message;
    header("Location: pagina_profil_utilizator.php#rezervari");
    exit();
}

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

$user_details = [];
$sql_user = "
    SELECT 
        M.IDmembru, 
        M.NumeMembru, 
        M.PrenumeMembru,
        M.StatusMembru,
        M.DataInscriereMembru,
        U.NumeUtilizator,
        U.Email
    FROM MEMBRU M
    JOIN UTILIZATOR U ON M.IDMembru = U.IDMembru
    WHERE M.IDmembru = ?
    LIMIT 1
";

$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $id_membru);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows > 0) {
    $user_details = $result_user->fetch_assoc();
} else {
    session_destroy();
    header("Location: pagina_login.php");
    exit();
}
$stmt_user->close();

$rezervari_active = [];
$sql_rezervari = "
    SELECT 
        C.TitluCarte, 
        GROUP_CONCAT(CONCAT(A2.NumeAutor, ' ', A2.PrenumeAutor) SEPARATOR ', ') AS Autor,
        AC.DataActiune AS DataRezervare,
        DATE_ADD(AC.DataActiune, INTERVAL 48 HOUR) AS DataExpirare,
        R.TaxaRezervare
    FROM ACTIUNE AC
    JOIN REZERVARE R ON AC.IDactiune = R.IDactiune
    JOIN CARTE C ON AC.IDcarte = C.IDcarte
    LEFT JOIN AUTOR_CARTE AC2 ON C.IDcarte = AC2.IDcarte
    LEFT JOIN AUTOR A2 ON AC2.IDautor = A2.IDautor
    WHERE AC.IDmembru = ?
      AND AC.TipActiune = 'Rezervare'
      AND AC.StatusCarteActiune = 'Rezervată'
      AND DATE_ADD(AC.DataActiune, INTERVAL 48 HOUR) > NOW()
    GROUP BY AC.IDactiune
    ORDER BY DataExpirare ASC
";
$stmt_rezervari = $conn->prepare($sql_rezervari);
$stmt_rezervari->bind_param("i", $id_membru);
$stmt_rezervari->execute();
$result_rezervari = $stmt_rezervari->get_result();
while ($row = $result_rezervari->fetch_assoc()) {
    $rezervari_active[] = $row;
}
$stmt_rezervari->close();

$imprumuturi_active = [];
$sql_active = "
    SELECT 
        C.TitluCarte, 
        GROUP_CONCAT(CONCAT(A2.NumeAutor, ' ', A2.PrenumeAutor) SEPARATOR ', ') AS Autor,
        AC.DataActiune AS DataImprumut, 
        DATE_ADD(AC.DataActiune, INTERVAL I.PerioadaZile DAY) AS DataScadenta
    FROM ACTIUNE AC
    JOIN IMPRUMUT I ON AC.IDactiune = I.IDactiune
    JOIN CARTE C ON AC.IDcarte = C.IDcarte
    JOIN AUTOR_CARTE AC2 ON C.IDcarte = AC2.IDcarte
    JOIN AUTOR A2 ON AC2.IDautor = A2.IDautor
    WHERE AC.IDmembru = ? 
      AND AC.TipActiune = 'Împrumut'
      AND AC.StatusCarteActiune = 'Împrumutată'
    GROUP BY AC.IDactiune
    ORDER BY DataScadenta ASC
";
$stmt_active = $conn->prepare($sql_active);
$stmt_active->bind_param("i", $id_membru);
$stmt_active->execute();
$result_active = $stmt_active->get_result();
while ($row = $result_active->fetch_assoc()) {
    $imprumuturi_active[] = $row;
}
$stmt_active->close();

$istoric_imprumuturi = [];
$sql_istoric = "
    SELECT 
        C.TitluCarte, 
        AC.DataActiune AS DataReturnare
    FROM ACTIUNE AC
    JOIN CARTE C ON AC.IDcarte = C.IDcarte
    WHERE AC.IDmembru = ? 
      AND AC.TipActiune = 'Împrumut' 
      AND AC.StatusCarteActiune = 'Returnată'
    ORDER BY AC.DataActiune DESC
    LIMIT 5
";

$stmt_istoric = $conn->prepare($sql_istoric);
$stmt_istoric->bind_param("i", $id_membru);
$stmt_istoric->execute();
$result_istoric = $stmt_istoric->get_result();
while ($row = $result_istoric->fetch_assoc()) {
    $istoric_imprumuturi[] = $row;
}
$stmt_istoric->close();

$conn->close();

function formatDate($date) {
    if ($date === 'N/A') return 'N/A';
    if (strtotime($date) === false) {
        return 'Data invalidă';
    }
    return date("d.m.Y", strtotime($date));
}

function formatDateTime($datetime) {
    if ($datetime === 'N/A') return 'N/A';
    if (strtotime($datetime) === false) {
        return 'Data invalidă';
    }
    return date("d.m.Y H:i", strtotime($datetime));
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: pagina_login.php");
    exit();
}

$prenume = $user_details['PrenumeMembru'] ?? 'N/A';
$nume = $user_details['NumeMembru'] ?? 'N/A';
$nume_complet = $prenume . ' ' . $nume;
$id_utilizator = $user_details['IDmembru'] ?? 'N/A';
$nume_utilizator = $user_details['NumeUtilizator'] ?? 'N/A';
$email = $user_details['Email'] ?? 'necunoscut@student.ro'; 
$data_inregistrare = formatDate($user_details['DataInscriereMembru'] ?? 'N/A');
$statut_membru = $user_details['StatusMembru'] ?? 'Inactiv';

$status_class = "status-available";
if (count($imprumuturi_active) > 0) {
    $status_class = "status-rented"; 
} else if ($statut_membru !== 'Activ') {
    $status_class = "status-rented"; 
}

$statut_afisat = $statut_membru;
if (count($imprumuturi_active) > 0) {
    $statut_afisat .= " (cu " . count($imprumuturi_active) . " cărți împrumutate)";
}

?>
<!DOCTYPE html>
<html lang="ro">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Studențească | Profil Utilizator</title>
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
            padding: 0;
        }
        
        main section {
            padding: 1.25rem;
            margin: 1.25rem auto;
            max-width: 56.25rem;
            background-color: rgb(153, 62, 62);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
            color: white;
        }

        h1, h2 {
            color: white;
            border-bottom: 0.125rem solid #ffdd57;
            padding-bottom: 0.3125rem;
            font-size: 1.5rem;
        }

        .link-button {
            background-color: #28a745;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.3125rem;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 0.625rem;
            margin-right: 0.625rem;
        }
        
        .link-button:hover { 
            background-color: #1e7e34;
        }

        #disclaimer {
            background-color: black;
            padding: 1rem;
            color: white;
            text-align: center;
        }

        .user-details p {
            margin-bottom: 0.625rem;
            font-size: 1.1rem;
        }
        
        .user-details strong {
            display: inline-block;
            width: 9.375rem;
            color: #ffdd57;
        }

        .card-list {
            list-style: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .card-list li {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 0.625rem;
            border-radius: 0.3125rem;
            border-left: 0.3125rem solid #007bff;
            font-size: 0.95rem; 
        }
        
        .card-list li strong {
            color: #ffdd57;
        }
        
        .reservation-item {
            border-left: 0.3125rem solid #ff9800 !important;
            background-color: rgba(255, 152, 0, 0.2) !important;
            color: white;
        }

        .status-rented {
            color: #ff4d4d;
            font-weight: bold;
        }

        .status-available {
            color: #28a745;
            font-weight: bold;
        }

        .modal {
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            display: none; 
            padding-top: 3.125rem;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 3.125rem auto;
            padding: 1.25rem;
            border: 0.0625rem solid #888;
            width: 80%;
            max-width: 31.25rem;
            border-radius: 0.625rem;
            color: black;
            box-shadow: 0 0.25rem 0.5rem 0 rgba(0,0,0,0.2), 0 0.375rem 1.25rem 0 rgba(0,0,0,0.19);
            animation-name: animatetop;
            animation-duration: 0.4s;
        }
        
        .modal-content input[type="text"],
        .modal-content input[type="password"],
        .modal-content input[type="email"],
        .modal-content input[type="number"] {
            width: 100%;
            padding: 0.625rem;
            margin-top: 0.3125rem;
            margin-bottom: 0.9375rem;
            border: 0.0625rem solid #ccc;
            border-radius: 0.3125rem;
            box-sizing: border-box;
            font-size: 1rem;
        }
        
        .modal-content button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 0.625rem 1rem;
            border: none;
            border-radius: 0.3125rem;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            margin-top: 0.625rem;
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

        @keyframes animatetop {
            from {top:-18.75rem; opacity:0} 
            to {top:0; opacity:1}
        }
        
        .header-image {
             margin-left: auto;
             margin-right: auto;
             display: block;
        }
        
        .alert-message {
            padding: 0.625rem;
            margin: 1rem auto;
            max-width: 56.25rem;
            background-color: #ffdd57;
            color: #1a1a1a;
            border-radius: 0.3125rem;
            text-align: center;
            font-weight: bold;
        }

        @media (min-width: 56.25rem) {
            
            #container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto 1fr auto;
                grid-template-areas:
                    "header"
                    "nav"
                    "main"
                    "footer";
                max-width: 75rem;
                margin: 0 auto;
            }

            main {
                padding: 1.25rem;
            }

            main section {
                max-width: 100%;
                padding: 1.5rem;
            }

            #imprumuturi .card-list, #istoric .card-list, #rezervari .card-list {
                flex-direction: row;
                flex-wrap: wrap;
            }
            
            #imprumuturi .card-list li, #istoric .card-list li, #rezervari .card-list li {
                flex: 1 1 calc(50% - 1rem);
                box-sizing: border-box;
            }
            
            .alert-message {
                max-width: 56.25rem;
            }
        }
        
        @media (max-width: 56.25rem) {
            
            header h1 {
                font-size: 1.75rem;
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
            
            main section {
                margin: 1rem auto;
            }

            .user-details strong {
                width: 7.5rem;
            }
            
            .card-list li {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body id="top">
    <div id="container">
        <header>
            <img src="poze\logo_biblioteca.jpg" alt="logobiblioteca" class="header-image">
            <h1>Biblioteca Studențească <span style="font-size: 0.7em;">— Profil Utilizator</span></h1>
        </header>

        <nav>
            <ul>
                <li><a href="index.php">Acasă</a></li>
                <li><a href="#profil">Profilul Meu</a></li>
                <li><a href="#imprumuturi">Cărți Împrumutate</a></li>
                <li><a href="#rezervari">Rezervări</a></li>
                <li><a href="#istoric">Istoric</a></li>
                <li><a href="#setari">Setări Cont</a></li>
                <li><a href="pagina_login.php?action=logout" class="link-button" style="background-color: #dc3545; padding: 0.3125rem 0.625rem;">Delogare</a></li>
            </ul>
        </nav>
        
        <main>
            <?php if (!empty($message)): ?>
                <div class="alert-message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <section id="profil">
                <h2>Bun venit, <?php echo htmlspecialchars($prenume); ?>! 👋</h2>
                <div class="user-details">
                    <p><strong>Nume Complet:</strong> <?php echo htmlspecialchars($nume_complet); ?></p>
                    <p><strong>Nume Utilizator:</strong> <?php echo htmlspecialchars($nume_utilizator); ?></p>
                    <p><strong>ID Membru:</strong> <?php echo htmlspecialchars($id_utilizator); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                    <p><strong>Data Înregistrării:</strong> <?php echo formatDate($user_details['DataInscriereMembru'] ?? 'N/A'); ?></p>
                    <p><strong>Statut:</strong> <span class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($statut_afisat); ?></span></p>
                </div>
                <a href="#setari" class="link-button">Editează Profil</a>
                <button id="rezervaCarteBtn" class="link-button" style="background-color: #ff9800; color: white;">Rezervă o Carte (48 de ore)</button>
            </section>
            
            <section id="rezervari"> 
                <h2>Rezervări Active 🕒</h2>
                <ul class="card-list">
                    <?php if (count($rezervari_active) > 0): ?>
                        <?php foreach ($rezervari_active as $rezervare): ?>
                            <li class="reservation-item">
                                <strong>Titlu:</strong> <?php echo htmlspecialchars($rezervare['TitluCarte']); ?><br>
                                <strong>Autor:</strong> <?php echo htmlspecialchars($rezervare['Autor'] ?? 'N/A'); ?><br>
                                <strong>Taxă Rezervare:</strong> <?php echo htmlspecialchars($rezervare['TaxaRezervare']); ?> RON<br>
                                <strong>Rezervat la:</strong> <?php echo formatDateTime($rezervare['DataRezervare']); ?><br>
                                <strong>Expiră la:</strong> <span style="font-weight: bold; color: #ffdd57;"><?php echo formatDateTime($rezervare['DataExpirare']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Nu aveți rezervări active în prezent.</li>
                    <?php endif; ?>
                </ul>
                <p style="margin-top: 0.9375rem; color: #ffdd57;">Vă rugăm să ridicați cărțile de la ghișeu înainte de data și ora de expirare.</p>
            </section>
            
            <section id="imprumuturi"> 
                <h2>Cărți Împrumutate 📖</h2>
                <ul class="card-list">
                    <?php if (count($imprumuturi_active) > 0): ?>
                        <?php foreach ($imprumuturi_active as $carte): ?>
                            <li>
                                <strong>Titlu:</strong> <?php echo htmlspecialchars($carte['TitluCarte']); ?><br>
                                <strong>Autor:</strong> <?php echo htmlspecialchars($carte['Autor']); ?><br>
                                <strong>Data Împrumut:</strong> <?php echo formatDate($carte['DataImprumut']); ?> | <strong>Data Scadenței:</strong> <?php echo formatDate($carte['DataScadenta']); ?> ⏳<br>
                                <strong>Status:</strong> <span class="status-rented">Împrumutată</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Nu ai cărți împrumutate în prezent. 🎉</li>
                    <?php endif; ?>
                </ul>
                <p style="margin-top: 0.9375rem;">Poți returna cărțile la orice ghișeu al bibliotecii în timpul programului.</p>
            </section>
            
            <section id="istoric">
                <h2>Istoric Împrumuturi 📜</h2>
                <ul class="card-list">
                    <?php if (count($istoric_imprumuturi) > 0): ?>
                        <?php foreach ($istoric_imprumuturi as $istoric): ?>
                            <li>
                                <strong>Titlu:</strong> <?php echo htmlspecialchars($istoric['TitluCarte']); ?><br>
                                <strong>Data Returnării:</strong> <?php echo formatDate($istoric['DataReturnare']); ?> | <strong>Status:</strong> <span class="status-available">Returnată</span>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>Nu există înregistrări în istoricul recent.</li>
                    <?php endif; ?>
                </ul>
                <a href="#" class="link-button" style="background-color: #007bff;">Vezi Istoric Complet</a>
            </section>

            <section id="setari">
                <h2>Setări Cont ⚙️</h2>
                <p>Aici poți actualiza informațiile de contact, schimba parola, adresa de e-mail sau gestiona notificările.</p>
                <button id="schimbaParolaBtn" class="link-button" style="background-color: #ffc107; color: black;">Schimbă Parola</button>
                <button id="schimbaEmailBtn" class="link-button" style="background-color: #ffc107; color: black;">Schimbă Adresa de Email</button>
            </section>

            <footer>
                <section id="disclaimer">
                    <p style="text-align: center; color:white;">© 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
                </section>
            </footer>
        </main>
        
    </div>

    <a href="#top" class="back-to-top">⬆️ Mergi sus</a>

    <div id="modalParola" class="modal">
        <div class="modal-content">
            <span class="close" data-modal-id="modalParola">&times;</span>
            <h3>Schimbă Parola</h3>
            <form action="pagina_profil_utilizator.php" method="POST">
                <input type="hidden" name="action" value="schimba_parola">
                <label for="parolaCurenta">Parola Curentă:</label>
                <input type="password" id="parolaCurenta" name="parolaCurenta" required>
                <label for="parolaNoua">Parola Nouă:</label>
                <input type="password" id="parolaNoua" name="parolaNoua" required>
                <label for="confirmaParolaNoua">Confirmă Parola Nouă:</label>
                <input type="password" id="confirmaParolaNoua" name="confirmaParolaNoua" required>
                <button type="submit">Actualizează Parola</button>
            </form>
        </div>
    </div>

    <div id="modalEmail" class="modal">
        <div class="modal-content">
            <span class="close" data-modal-id="modalEmail">&times;</span>
            <h3>Schimbă Adresa de Email</h3>
            <form action="pagina_profil_utilizator.php" method="POST">
                <input type="hidden" name="action" value="schimba_email">
                <label for="emailCurent">Adresa de Email Curentă:</label>
                <input type="email" id="emailCurent" name="emailCurent" value="<?php echo htmlspecialchars($email); ?>" disabled>
                <label for="emailNou">Adresa de Email Nouă:</label>
                <input type="email" id="emailNou" name="emailNou" required>
                <label for="parolaConfirmare">Parola (pentru confirmare):</label>
                <input type="password" id="parolaConfirmare" name="parolaConfirmare" required>
                <button type="submit">Actualizează Email</button>
            </form>
        </div>
    </div>
    
    <div id="modalRezervare" class="modal">
        <div class="modal-content">
            <span class="close" data-modal-id="modalRezervare">&times;</span>
            <h3>Rezervare Carte (48h)</h3>
            
            <p>
                Pentru a afla **ID-ul cărții**, vă rugăm consultați 
                <a href="inventar_carti.php" target="_blank" style="color: #007bff; font-weight: bold; text-decoration: underline;">Inventarul de Cărți (se deschide într-o fereastră nouă)</a>.
            </p>
            
            <p>Vă rugăm introduceți **ID-ul cărții** pe care doriți să o rezervați. Timpul de ridicare este de 48 de ore din momentul rezervării. Taxa de rezervare este de 5 RON.</p>
            <form action="pagina_profil_utilizator.php" method="POST">
                <input type="hidden" name="action" value="rezerva_carte">
                <label for="carteIdRezervare">ID Carte:</label>
                <input type="number" id="carteIdRezervare" name="IDcarte" min="1" required>
                <button type="submit">Confirmă Rezervarea</button>
            </form>
        </div>
    </div>

    <script>
        const modalParola = document.getElementById('modalParola');
        const modalEmail = document.getElementById('modalEmail');
        const modalRezervare = document.getElementById('modalRezervare');
        
        const btnParola = document.getElementById('schimbaParolaBtn');
        const btnEmail = document.getElementById('schimbaEmailBtn');
        const btnRezervare = document.getElementById('rezervaCarteBtn');
        
        const closeBtns = document.querySelectorAll('.close');

        function openModal(modal) {
            modal.style.display = 'block';
        }

        function closeModal(modal) {
            modal.style.display = 'none';
        }

        btnParola.onclick = function() {
            openModal(modalParola);
        }

        btnEmail.onclick = function() {
            openModal(modalEmail);
        }
        
        btnRezervare.onclick = function() {
            openModal(modalRezervare);
        }

        closeBtns.forEach(btn => {
            btn.onclick = function() {
                const modalId = this.getAttribute('data-modal-id');
                const modal = document.getElementById(modalId);
                closeModal(modal);
            }
        });

        window.onclick = function(event) {
            if (event.target == modalParola) {
                closeModal(modalParola);
            }
            if (event.target == modalEmail) {
                closeModal(modalEmail);
            }
            if (event.target == modalRezervare) {
                closeModal(modalRezervare);
            }
        }
    </script>
</body>
</html>