<?php
require_once 'config.php';
session_start();

function getDbConnection($servername, $username, $password, $dbname, $port) {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die("Conexiune eșuată: " . $conn->connect_error);
    }
    return $conn;
}

$login_error = '';
$register_message = '';
$conn = null; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $conn = getDbConnection($servername, $username, $password, $dbname, $port);
    if ($conn === null) {
        $login_error = "Eroare la conexiunea cu baza de date.";
        goto end_processing;
    }

    if (isset($_POST['login-submit'])) {
        $user_input = trim($_POST['utilizator']);
        $parola_input = $_POST['parola'];

        // 1. VERIFICARE ADMIN (root/password)
        if ($user_input === 'root' && $parola_input === 'password') {
            $_SESSION['IDmembru'] = 0; 
            $_SESSION['isAdmin'] = true; 
            header("Location: pagina_admin.php");
            goto end_processing;
        }

        // 2. VERIFICARE UTILIZATOR OBISNUIT
        $sql = "SELECT U.IDmembru, U.NumeUtilizator, U.ParolaUtilizator FROM UTILIZATOR U WHERE U.NumeUtilizator = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            $hashed_password_db = $user['ParolaUtilizator'];
            $isAuthenticated = false;

            // A. VERIFICARE PAROLĂ CRIPTATĂ (modernă)
            if (password_verify($parola_input, $hashed_password_db)) {
                $isAuthenticated = true;
                
                // Opțional: Re-hash dacă algoritmul s-a schimbat
                if (password_needs_rehash($hashed_password_db, PASSWORD_DEFAULT)) {
                    $new_hash = password_hash($parola_input, PASSWORD_DEFAULT);
                    $update_sql = "UPDATE UTILIZATOR SET ParolaUtilizator = ? WHERE IDmembru = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("si", $new_hash, $user['IDmembru']);
                    $update_stmt->execute();
                    $update_stmt->close();
                }

            // B. FALLBACK: VERIFICARE PAROLĂ NECRIPTATĂ (istorică)
            // Daca parola stocata NU este hash, o consideram ca fiind text clar.
            } elseif ($parola_input === $hashed_password_db && strlen($hashed_password_db) < 60) {
                $isAuthenticated = true;
                
                // MIGREAZĂ parola veche la hash nou
                $new_hash = password_hash($parola_input, PASSWORD_DEFAULT);
                $update_sql = "UPDATE UTILIZATOR SET ParolaUtilizator = ? WHERE IDmembru = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("si", $new_hash, $user['IDmembru']);
                $update_stmt->execute();
                $update_stmt->close();
            }

            if ($isAuthenticated) {
                $_SESSION['IDmembru'] = $user['IDmembru'];
                header("Location: pagina_profil_utilizator.php");
                $stmt->close();
                goto end_processing;
            } else {
                $login_error = "Parolă incorectă.";
            }
        } else {
            $login_error = "Utilizator inexistent.";
        }
        $stmt->close();
    } 
    
    elseif (isset($_POST['register-submit'])) {
        $prenume = trim($_POST['reg_prenume']);
        $nume = trim($_POST['reg_nume']);
        $nume_utilizator = trim($_POST['utilizator']);
        $email = trim($_POST['mail']);
        $parola = $_POST['parola'];
        
        $sql_check = "SELECT IDMembru FROM UTILIZATOR WHERE NumeUtilizator = ? OR Email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $nume_utilizator, $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $register_message = "Eroare: Numele de utilizator sau E-mailul există deja.";
            $stmt_check->close();
            goto end_processing;
        }
        $stmt_check->close();

        $parola_hash = password_hash($parola, PASSWORD_DEFAULT);
        
        $status_initial = 'Inactiv'; 
        $data_inscriere = date('Y-m-d'); 
        
        $sql_membru = "INSERT INTO MEMBRU (NumeMembru, PrenumeMembru, StatusMembru, DataInscriereMembru) VALUES (?, ?, ?, ?)";
        $stmt_membru = $conn->prepare($sql_membru);
        $stmt_membru->bind_param("ssss", $nume, $prenume, $status_initial, $data_inscriere);
        
        if ($stmt_membru->execute()) {
            $id_membru_nou = $conn->insert_id; 
            $stmt_membru->close();
            
            $sql_utilizator = "INSERT INTO UTILIZATOR (IDMembru, NumeUtilizator, ParolaUtilizator, Email) VALUES (?, ?, ?, ?)";
            $stmt_utilizator = $conn->prepare($sql_utilizator);
            $stmt_utilizator->bind_param("isss", $id_membru_nou, $nume_utilizator, $parola_hash, $email);
            
            if ($stmt_utilizator->execute()) {
                $stmt_utilizator->close();
                $register_message = "Succes: Contul a fost creat! Te poți autentifica.";
            } else {
                $conn->query("DELETE FROM MEMBRU WHERE IDmembru = $id_membru_nou");
                $register_message = "Eroare la crearea contului (DB Fail).";
            }
        } else {
            $register_message = "Eroare la înregistrarea membrului.";
        }
    }
}

end_processing:
if ($conn !== null) {
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Autentificare & Înregistrare | Biblioteca Studențească</title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico">
    <style>
        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px 20px 80px 20px;
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        header {
            background-color: #840909;
            color: white;
            padding: 30px 0;
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
            position: relative; 
        }
        .logo-container {
            margin-bottom: 15px;
        }
        .logo-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: contain;
            background-color: rgb(37, 35, 35);
            padding: 5px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }
        .header-link {
            position: absolute;
            top: 10px;
            right: 15px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 5px;
            background-color: #a02020;
            transition: background-color 0.3s;
        }
        .header-link:hover {
            background-color: #b33939;
        }
        h1 {
            color:white;
            font-size: 1.5em;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        h2 {
            display: none;
        }
        .form-content {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            width: 100%;
            max-width: 900px;
        }
        section {
            background-color: rgb(153, 62, 62);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: bold;
            color:black;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input:focus {
            border-color: #007bff;
            outline: none;
        }
        button {
            background-color: #007bff; 
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            width: 100%;
            margin-top: 25px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        #register-section button {
            background-color: #28a745;
        }
        #register-section button:hover {
            background-color: #1e7e34;
        }
        .link-forgot {
            text-align: center; 
            margin-top: 10px;
        }
        .link-forgot a {
            color:white; 
            text-decoration: none;
        }
        .error-message {
            color: #ffdd57; 
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        .success-message {
            color: #28a745; 
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
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
            z-index: 100;
        }
    </style>
</head>
<body>
    
    <header>
        <a href="index.php" class="header-link">Acasă</a>
        <div class="logo-container">
            <img src="poze\logo_biblioteca.jpg" alt="Logo Biblioteca Studențească">
        </div>
    </header>

    <div class="form-content">
        <section id="login-section">
            <h1>Ai deja cont? 🔒</h1>
            
            <?php if (!empty($login_error)): ?>
                <p class="error-message"><?php echo $login_error; ?></p>
            <?php endif; ?>
            <?php if (!empty($register_message) && strpos($register_message, 'Succes') === 0): ?>
                <p class="success-message"><?php echo $register_message; ?></p>
            <?php endif; ?>

            <form action="pagina_login.php" method="POST">
                <label for="login-user">Utilizator</label>
                <input type="text" id="login-user" name="utilizator" required>
                
                <label for="login-pass">Parolă</label>
                <input type="password" id="login-pass" name="parola" required> 
                
                <button type="submit" name="login-submit">AUTENTIFICARE</button>
            </form>
            <p class="link-forgot"><a href="#">Ai uitat parola?</a></p>
        </section>

        <section id="register-section"> 
            <h1>Nu ai cont? Înregistrează-te! 📝</h1>

            <?php if (!empty($register_message) && strpos($register_message, 'Eroare') === 0): ?>
                <p class="error-message"><?php echo $register_message; ?></p>
            <?php endif; ?>
            
            <form action="pagina_login.php" method="POST">
                <input type="hidden" name="register-submit" value="1">

                <label for="reg-prenume">Prenume</label>
                <input type="text" id="reg-prenume" name="reg_prenume" required>

                <label for="reg-nume">Nume</label>
                <input type="text" id="reg-nume" name="reg_nume" required>
                
                <label for="reg-user">Nume Utilizator</label>
                <input type="text" id="reg-user" name="utilizator" required>
                
                <label for="reg-mail">Email</label>
                <input type="email" id="reg-mail" name="mail" required>
                
                <label for="reg-pass">Parolă</label>
                <input type="password" id="reg-pass" name="parola" required>
                
                <button type="submit" name="register-submit">CREARE CONT!</button>
            </form>
        </section>
    </div>
    
    <footer>
        <p>&copy; 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
    </footer>
    
</body>
</html>