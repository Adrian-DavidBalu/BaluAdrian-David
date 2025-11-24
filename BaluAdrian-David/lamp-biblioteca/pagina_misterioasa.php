<?php
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret | Biblioteca Studențească</title>
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
            color: white;
            line-height: 1.5;
        }

        #container {
            display: grid;
            grid-template-areas:
                "header"
                "main"
                "footer";
            gap: 0;
            min-height: 100vh;
        }
        
        header { grid-area: header; }
        main { grid-area: main; }
        footer { grid-area: footer; }

        header {
            background-color: #840909;
            color: white;
            padding: 1.25rem 0;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            color: white;
            border-bottom: 0.125rem solid #ffdd57;
            padding-bottom: 0.625rem;
            margin: 0 1.25rem 0.625rem 1.25rem;
            display: inline-block;
        }

        main section {
            padding: 2.5rem 1.25rem;
            margin: 1.25rem auto;
            max-width: 56.25rem;
            background-color: rgb(153, 62, 62);
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
            text-align: center;
            color: white;
        }

        h2 {
            font-size: 1.5rem;
            margin-top: 0;
        }

        .image-container {
            display: flex;
            justify-content: space-around;
            gap: 1.25rem;
            flex-wrap: wrap;
            margin-top: 1.875rem;
            margin-bottom: 2.5rem;
        }

        .mistery-link {
            display: inline-block;
            transition: transform 0.3s ease-in-out;
        }

        .mistery-link:hover {
            transform: scale(1.05);
        }

        .mistery-link img {
            width: 9.375rem;
            height: 13.75rem;
            border: 0.3125rem solid #ffdd57;
            border-radius: 0.5rem;
            object-fit: cover;
            display: block;
        }

        .goodreads-section {
            background-color: #333;
            color: white;
            padding: 1.25rem;
            margin: 1.25rem auto;
            max-width: 56.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.1);
            text-align: center;
        }

        .goodreads-section a {
            color: #ffdd57;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }

        .goodreads-section a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .button-container {
            margin-top: 1.875rem;
            display: flex;
            justify-content: center;
            gap: 0.9375rem;
        }

        .mistery-btn {
            background-color: #dc3545;
            color: white;
            border: 0.1875rem solid #b33939;
            border-radius: 50%;
            width: 2.8125rem;
            height: 2.8125rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .mistery-btn:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
        }

        .modal-content {
            background-color: #840909;
            color: white;
            margin: 9.375rem auto;
            padding: 1.25rem;
            border: 0.3125rem solid #ffdd57;
            width: 80%;
            max-width: 25rem;
            text-align: center;
            font-size: 1.2rem;
            border-radius: 0.625rem;
        }

        .warning-text {
            color: #ffdd57;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            display: block;
        }

        footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 0.625rem 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            z-index: 5;
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
                font-size: 1.75rem;
            }

            main section {
                padding: 1.5rem 0.625rem;
                margin: 0.625rem auto;
            }
            
            .image-container {
                gap: 1rem;
                justify-content: center;
            }
            
            .mistery-link img {
                width: 7.5rem;
                height: 11.25rem;
            }

            .warning-text {
                font-size: 1rem;
                padding: 0 0.625rem;
            }
            
            .goodreads-section {
                margin: 0.625rem auto 4.375rem auto;
            }
        }
    </style>
</head>
<body id="top">
    <div id="container">
        <header>
            <h1>Aici se ascunde un secret... (Bine, poate 3...)🤫</h1>
        </header>
        
        <main>
            <section>
                <h2>Poți deschide fișierul?</h2>
                <p>Apasă pe oricare dintre imagini pentru a dezvălui documentele misterioase.</p>

                <div class="image-container">
                    <a href="carti_format_pdf/Albert_Camus-Strainul.pdf" target="_blank" class="mistery-link">
                        <img src="poze/poza_mister.png" alt="Poza Misterioasă 1">
                    </a>

                    <a href="carti_format_pdf/Aldous_Huxley-Minunata_lume_noua.pdf" target="_blank" class="mistery-link">
                        <img src="poze/poza_mister.png" alt="Poza Misterioasă 2">
                    </a>

                    <a href="carti_format_pdf/Alexandre_Dumas_fiul-Dama_cu_camelii.pdf" target="_blank" class="mistery-link">
                        <img src="poze/poza_mister.png" alt="Poza Misterioasă 3">
                    </a>
                </div>
                
                <span class="warning-text">
                    Ești pierdut în BIBLIOTECĂ! Nu știu de ce ai vrea să pleci, dar ai o singură șansă să te întorci ACASĂ!
                </span>

                <div id="mistery-buttons" class="button-container">
                    <button class="mistery-btn" data-action="trap">1</button>
                    <button class="mistery-btn" data-action="trap">2</button>
                    <button class="mistery-btn" data-action="trap">3</button>
                    <button class="mistery-btn" data-action="home">4</button>
                    <button class="mistery-btn" data-action="trap">5</button>
                </div>
            </section>

            <div class="goodreads-section">
                <p>
                    Link către profilul meu Goodreads pentru mai multe recomandări de lectură: 
                    <a href="https://www.goodreads.com/user/show/157970379-adrian-david-balu" target="_blank">Goodreads Balu Adrian-David</a> 📚
                </p>
            </div>
        </main>

        <div id="mistery-modal" class="modal">
            <div class="modal-content">
                <p>Ai pierdut! Nu ai voie să pleci de aici până nu citești măcar o carte! 😠</p>
            </div>
        </div>

        <footer>
            <p>© 2025 Biblioteca Studențească. Locul cel mai misterios din campus.</p>
        </footer>
    </div>
    
    <a href="#top" class="back-to-top">⬆️ Mergi sus</a>

    <script>
        const buttonContainer = document.getElementById('mistery-buttons');
        const buttons = buttonContainer.querySelectorAll('.mistery-btn');
        const modal = document.getElementById('mistery-modal');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                
                if (action === 'home') {
                    window.location.href = 'index.php';
                } else {
                    buttonContainer.style.display = 'none';
                    modal.style.display = 'block';
                }
            });
        });

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>