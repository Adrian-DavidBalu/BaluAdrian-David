<?php
// Adaugă config.php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca Studențească | Acasă</title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico">
    <style>
        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: black;
        }
        .header-image
        {
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
        section {
            padding: 20px;
            margin: 20px auto;
            max-width: 900px; 
            background-color: rgb(153, 62, 62);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            color: white; 
        h1, h2 {
            color: white;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }
        .hero-img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 10px;
        }
        .link-button {
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .link-button:hover {
            background-color: #1e7e34;
        }
        #disclaimer{
            background-color: black;
        }
    </style>
</head>
<body>
    <header>
        <a href="pagina_misterioasa.php">
        <img src="poze\logo_biblioteca.jpg" alt="logobiblioteca" class="header-image"></a>
        <h1>Biblioteca Studențească <span style="font-size: 0.7em;">— Din 2017</span></h1>
    </header>

    <nav>
        <ul>
            <li><a href="#">Acasă</a></li>
            <li><a href="inventar_carti.php">Inventar</a></li> 
            <li><a href="#despre">Despre Noi</a></li>
            <li><a href="#program">Program</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
    </nav>
    
    <section>
        <img src="https://d36tnp772eyphs.cloudfront.net/blogs/1/2020/09/The-Peabody-Library-building.jpg" alt="Imagine cu o bibliotecă elegantă" class="hero-img" width="900" height="200">
    </section>

    <section id="despre"> 
        <h2>Despre Noi 📚</h2>
        <p>
            <strong>Biblioteca Studențească</strong> a fost fondată acum 8 ani, în anul <strong>2017</strong>, cu scopul de a oferi un spațiu de studiu liniștit și resurse academice de ultimă oră pentru comunitatea noastră universitară. 
        </p>
        <p>
            De-a lungul anilor, am crescut constant colecția noastră, ajungând la peste 
            <strong>50.000 de volume</strong>, incluzând cărți de specialitate, reviste științifice și o vastă secțiune de literatură. Ne mândrim că suntem un pilon de suport în educația și cercetarea studenților!
        </p>
    </section>
    
    <section id="program">
        <h2>Program 🗓️</h2>
        <p>Vă așteptăm să ne vizitați conform programului de mai jos:</p>
        <p>
            <strong>Luni - Vineri:</strong> 08:00 - 20:00<br>
            <strong>Sâmbătă:</strong> 10:00 - 16:00<br>
            <strong>Duminică:</strong> Închis
        </p>
    </section>

    <section id="contact">
        <h2>Contact 📞</h2>
        <p>Suntem disponibili tot timpul în intervalul orar trecut în Program și ne puteți găsi la:</p>
        <p>
            <strong>📍Adresă:</strong>
            <a href="https://maps.google.com/?q=Strada+Studentilor+Nr.+10" target="_blank">Str. Studenților 10</a><br>

            <strong>☎️Telefon:</strong>
            <a href="tel:0712345678">0712345678</a><br>

            <strong>📧Email:</strong> 
            <a href="mailto:biblioteca_studenteasca@gmail.ro">biblioteca_studenteasca@gmail.ro</a><br>
        </p>
    </section>

    <section>
        <p style="text-align: center;">Vrei acces online la contul tău? Creează-ți cont acum sau conectează-te!</p>
        <p style="text-align: center;">
            <a href="pagina_login.php" class="link-button">✨SPRE PAGINA DE LOGIN!✨</a>
        </p>
    </section>

    <section id="disclaimer">
        <p style="text-align: center; color:white;">© 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
    </section>
</body>
</html>