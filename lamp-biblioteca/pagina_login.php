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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login-submit'])) {
    $conn = getDbConnection($servername, $username, $password, $dbname, $port);
    $user_input = $conn->real_escape_string($_POST['utilizator']);
    $parola_input = $_POST['parola'];
    $sql = "SELECT U.IDmembru, U.NumeUtilizator, U.ParolaUtilizator FROM UTILIZATOR U WHERE U.NumeUtilizator = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($user['ParolaUtilizator'] === $parola_input) {
            $_SESSION['IDmembru'] = $user['IDmembru'];
            header("Location: pagina_profil_utilizator.php");
            exit();
        } else {
            $login_error = "Parolă incorectă.";
        }
    } else {
        $login_error = "Utilizator inexistent.";
    }
    $stmt->close();
    $conn->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register-submit'])) {
    $register_message = "Înregistrarea a fost procesată (logică completă omisă).";
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
                              max-width: 800px;
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
                    <div class="logo-container">
                              <img src="poze\logo_biblioteca.jpg" alt="Logo Biblioteca Studențească">
                    </div>
                    </header>

          <div class="form-content">
                    <section id="login-section">
                              <h1>Ai deja cont? 🔒</h1>
                              
                              <?php if (isset($login_error)): ?>
                <p class="error-message"><?php echo $login_error; ?></p>
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

            <?php if (isset($register_message)): ?>
                <p class="error-message" style="color: #28a745;"><?php echo $register_message; ?></p>
            <?php endif; ?>

                              <form action="pagina_login.php" method="POST">
                                        <label for="reg-user">Nume Utilizator</label>
                                        <input type="text" id="reg-user" name="utilizator" required>
                                        
                                        <label for="reg-mail">Email</label>
                                        <input type="email" id="reg-mail" name="mail" required>
                                        
                                        <label for="reg-nume">Nume complet</label>
                                        <input type="text" id="reg-nume" name="nume_complet" required>
                                        
                                        <label for="reg-tel">Număr telefon</label>
                                        <input type="tel" id="reg-tel" name="numar_telefon">
                                        
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