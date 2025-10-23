<?php
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>??? | Biblioteca Studențească</title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico">
    <style>
        body {
            background-color: #545353;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: white;
            min-height: 100vh;
        }
        header {
            background-color: #840909;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
        h1 {
            font-size: 2em;
            color: white;
            border-bottom: 2px solid #ffdd57;
            padding-bottom: 10px;
            margin: 0 0 10px 0;
        }
        section {
            padding: 40px 20px;
            margin: 20px auto;
            max-width: 900px; 
            background-color: rgb(153, 62, 62);
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .image-container {
            display: flex;
            justify-content: space-around;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
            margin-bottom: 40px;
        }
        .mistery-link {
            display: inline-block;
            transition: transform 0.3s ease-in-out;
        }
        .mistery-link:hover {
            transform: scale(1.05);
        }
        .mistery-link img {
            width: 150px; 
            height: 220px; 
            border: 5px solid #ffdd57;
            border-radius: 8px;
            object-fit: cover;
            display: block;
        }
        .goodreads-section {
            background-color: #333;
            color: white;
            padding: 20px;
            margin: 20px auto;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .mistery-btn {
            background-color: #dc3545; 
            color: white;
            border: 3px solid #b33939;
            border-radius: 50%;
            width: 45px; 
            height: 45px; 
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2em;
        }
        .mistery-btn:hover {
            background-color: #c82333;
            transform: scale(1.1);
        }
        .modal {
            display: none; 
            position: fixed;
            z-index: 1; 
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
            margin: 15% auto;
            padding: 20px;
            border: 5px solid #ffdd57;
            width: 80%;
            max-width: 400px;
            text-align: center;
            font-size: 1.2em;
            border-radius: 10px;
        }
        .warning-text {
            color: #ffdd57;
            font-size: 1.1em;
            margin-bottom: 20px;
            display: block;
        }
        footer {
            background-color: #000;
            color: white;
            text-align: center;
            padding: 5px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Aici se ascunde un secret... (Bine, poate 3...)🤫</h1>
    </header>
    
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

    <div id="mistery-modal" class="modal">
        <div class="modal-content">
            <p>Ai pierdut! Nu ai voie să pleci de aici până nu citești măcar o carte! 😠</p>
        </div>
    </div>

    <footer>
        <p>© 2025 Biblioteca Studențească. Locul cel mai misterios din campus.</p>
    </footer>

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