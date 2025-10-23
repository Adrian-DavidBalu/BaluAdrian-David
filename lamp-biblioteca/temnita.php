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
            'class' => 'btn-nu'
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
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.5);
            max-width: 600px;
            width: 90%;
            border: 3px solid #840909;
        }
        
        h2 {
            color: #f7b731;
            margin-bottom: 40px;
            font-size: 2em;
            text-shadow: 2px 2px 5px #000;
        }

        .button-group a {
            display: inline-block;
            margin: 15px 10px;
            padding: 15px 35px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1em;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px #444;
        }

        .btn-initial {
            background-color: #555;
            color: white;
        }
        .btn-initial:hover {
            background-color: #777;
            transform: translateY(-2px);
            box-shadow: 0 6px #444;
        }

        .btn-da {
            background-color: #28a745;
            color: white;
            box-shadow: 0 4px #1e7e34;
        }
        .btn-da:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px #1e7e34;
        }

        .btn-nu {
            background-color: #dc3545;
            color: white;
            box-shadow: 0 4px #bd2130;
        }
        .btn-nu:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 6px #bd2130;
        }
    </style>
</head>
<body>
    <div class="temnita-container">
        <h2><?php echo $mesaj_principal; ?></h2>

        <div class="button-group">
            <?php foreach ($butoane as $buton): ?>
                <a href="<?php echo $buton['link']; ?>" class="<?php echo $buton['class']; ?>">
                    <?php echo $buton['text']; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>