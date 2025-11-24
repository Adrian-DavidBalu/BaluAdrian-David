<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Studențească | Acasă</title>
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

        main section, aside section {
            padding: 1rem;
            margin: 1.25rem auto; 
            max-width: 95%;
            background-color: rgb(153, 62, 62);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
            color: white;
        }

        main section h2, aside section h2 {
            color: white;
            border-bottom: 0.125rem solid #007bff;
            padding-bottom: 0.3125rem;
            font-size: 1.5rem;
        }

        .hero-img {
            display: block;
            width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin-bottom: 0.625rem;
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

        img {
            max-width: 100%; 
            height: auto; 
            display: block;
        }
        
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
            justify-content: space-around;
            padding: 1.25rem 0;
        }

        .product-card {
            background-color: white;
            color: #1a1a1a;
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.5rem rgba(0,0,0,0.2);
            padding: 1rem;
            flex: 1 1 30%;
            min-width: 15rem;
            box-sizing: border-box;
            text-align: center;
        }

        .product-card img {
            width: 100%;
            height: auto;
            border-radius: 0.3125rem;
            margin-bottom: 0.625rem;
        }

        .product-card h3 {
            margin-top: 0;
            font-size: 1.25rem;
            color: #840909;
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
        
        @media (min-width: 56.25rem) {
            
            #container {
                grid-template-columns: 1fr 3fr 1fr;
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
                padding-right: 1.25rem;
            }
        }
        
        @media (max-width: 56.25rem) {
            
            header h1 {
                font-size: 1.75rem; 
            }

            main section h2, aside section h2 {
                font-size: 1.25rem;
            }
            
            body {
                line-height: 1.5;
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

            .product-card {
                flex: 1 1 100%;
                margin-bottom: 1rem;
            }

            main section, aside section {
                text-align: center;
            }
            
            .header-image {
                margin: 0 auto 10px auto;
            }
        }
    </style>
</head>
<body id="top">
    <div id="container">
        <header>
            <a href="pagina_misterioasa.php">
                <img src="poze\logo_biblioteca.jpg" alt="logobiblioteca" class="header-image">
            </a>
            <h1>Biblioteca Studențească <span style="font-size: 0.7em;">— Din 2017</span></h1>
        </header>

        <nav>
            <ul>
                <li><a href="#top">Acasă</a></li>
                <li><a href="inventar_carti.php">Inventar</a></li> 
                <li><a href="#despre">Despre Noi</a></li>
                <li><a href="#program">Program</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        
        <main>
            <section>
                <img src="https://d36tnp772eyphs.cloudfront.net/blogs/1/2020/09/The-Peabody-Library-building.jpg" alt="Imagine cu o bibliotecă elegantă" class="hero-img">
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

        </main>

        <aside>
            <section id="contact">
                <h2>Contact 📞</h2>
                <p>Suntem disponibili tot timpul în intervalul orar trecut în Program și ne puteți găsi la:</p>
                <p>
                    <strong>📍Adresă:</strong>
                    <a href="https://maps.google.com/?q=Strada+Studentilor+Nr.+10" target="_blank" style="color: white;">Str. Studenților 10</a><br>

                    <strong>☎️Telefon:</strong>
                    <a href="tel:0712345678" style="color: white;">0712345678</a><br>

                    <strong>📧Email:</strong> 
                    <a href="mailto:biblioteca_studenteasca@gmail.ro" style="color: white;">biblioteca_studenteasca@gmail.ro</a><br>
                </p>
            </section>

            <section>
                <p style="text-align: center;">Vrei acces online la contul tău? Creează-ți cont acum sau conectează-te!</p>
                <p style="text-align: center;">
                    <a href="pagina_login.php" class="link-button">✨SPRE PAGINA DE LOGIN!✨</a>
                </p>
            </section>
        </aside>
        
        <footer>
            <section id="disclaimer">
                <p>© 2025 Biblioteca Studențească. Toate drepturile sunt rezervate!</p>
            </section>
        </footer>
    </div>
    
    <a href="#top" class="back-to-top">⬆️ Mergi sus</a>
</body>
</html>