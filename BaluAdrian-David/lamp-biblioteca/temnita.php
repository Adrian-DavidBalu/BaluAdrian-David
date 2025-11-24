<?php

$stare = isset($_GET['stare']) ? intval($_GET['stare']) : 1;

$titlu = "Temnița Bibliotecii | Acces Restricționat";
$mesaj_principal = "";
$butoane = [];

switch ($stare) {
    case 1:
        $mesaj_principal = "Ai urmărit hoțul, nu?";
        $butoane[] = [
            'text' => 'Da...',
            'link' => '?stare=2',
            'class' => 'btn-initial'
        ];
        break;

    case 2:
        $mesaj_principal = "Ai ajuns în peștera bibliotecii! Ca să ieși, răspunde la o întrebare. Ai citit vreo carte recent?";
        $butoane[] = [
            'text' => 'DA',
            'link' => 'index.php',
            'class' => 'btn-da'
        ];
        $butoane[] = [
            'text' => 'NU',
            'link' => 'pagina_inexistenta.php',
            'class' => 'btn-nu',
            'download_file' => 'poze/virus_mortal.jpg' 
        ];
        break;

    default:
        header('Location: temnita.php');
        exit;
}

?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titlu; ?></title>
    <link rel="icon" type="image/x-icon" href="poze/logo_biblioteca.ico"> 
    <style>
        :root {
            font-size: 16px;
        }
        
        body {
            background-image: url('poze/pestera.jpg'); 
            background-color: #1a1a1a;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; 
            text-align: center;
        }

        .temnita-container {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 3.125rem;
            border-radius: 0.9375rem;
            box-shadow: 0 0 1.875rem rgba(255, 0, 0, 0.5);
            max-width: 37.5rem;
            width: 90%;
            border: 0.1875rem solid #840909;
        }
        
        h2 {
            color: #f7b731;
            margin-bottom: 2.5rem;
            font-size: 2rem;
            text-shadow: 0.125rem 0.125rem 0.3125rem #000;
        }

        .button-group {
            display: flex;
            justify-content: center;
            flex-wrap: wrap; 
            gap: 0.9375rem;
        }

        .button-group a {
            display: inline-block;
            margin: 0.9375rem 0.625rem;
            padding: 0.9375rem 2.1875rem;
            border: none;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 0.25rem #444;
        }

        .btn-initial {
            background-color: #555;
            color: white;
            box-shadow: 0 0.25rem #444;
        }
        .btn-initial:hover {
            background-color: #777;
            transform: translateY(-0.125rem);
            box-shadow: 0 0.375rem #444;
        }

        .btn-da {
            background-color: #28a745;
            color: white;
            box-shadow: 0 0.25rem #1e7e34;
        }
        .btn-da:hover {
            background-color: #218838;
            transform: translateY(-0.125rem);
            box-shadow: 0 0.375rem #1e7e34;
        }

        .btn-nu {
            background-color: #dc3545;
            color: white;
            box-shadow: 0 0.25rem #bd2130;
        }
        .btn-nu:hover {
            background-color: #c82333;
            transform: translateY(-0.125rem);
            box-shadow: 0 0.375rem #bd2130;
        }
        
        .warning-text {
            color: #ff4d4d;
            font-weight: bold;
            font-size: 1.2rem;
            margin-top: 2rem;
            display: block;
        }

        .download-link {
            color: #f7b731;
            text-decoration: none;
            font-size: 1rem;
            margin-top: 1rem;
            display: block;
            transition: color 0.2s;
        }
        
        .download-link:hover {
            color: white;
            text-decoration: underline;
        }

        @media (max-width: 37.5rem) {
            
            .temnita-container {
                padding: 1.875rem;
                margin: 1.25rem;
            }

            h2 {
                font-size: 1.5rem;
                margin-bottom: 1.875rem;
            }

            .button-group a {
                padding: 0.75rem 1.5rem;
                font-size: 1rem;
                margin: 0.5rem 0;
                width: 100%;
                box-sizing: border-box;
            }
            
            .warning-text {
                font-size: 1rem;
            }
            
            .download-link {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="temnita-container">
        <h2><?php echo $mesaj_principal; ?></h2>

        <div class="button-group">
            <?php foreach ($butoane as $buton): ?>
                <?php if (isset($buton['download_file'])): ?>
                    <a href="<?php echo htmlspecialchars($buton['download_file']); ?>" class="<?php echo $buton['class']; ?>" download="virus_mortal.jpg">
                        <?php echo $buton['text']; ?>
                    </a>
                <?php else: ?>
                    <a href="<?php echo $buton['link']; ?>" class="<?php echo $buton['class']; ?>">
                        <?php echo $buton['text']; ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($stare == 2): ?>
            <span class="warning-text">ORICE AI FACE, NU FURA CARTEA BLESTEMATĂ</span>
            <a href="poze/cartea-interzisa.txt" download="cartea-interzisa.txt" class="download-link">
                Descarcă: cartea-interzisa.txt
            </a>
        <?php endif; ?>
    </div>
</body>
</html>