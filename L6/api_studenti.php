<?php
require_once 'config.php'; 

header('Content-Type: application/json');

//CONECTARE LA BAZA DE DATE
function getDbConnection($servername, $username, $password, $dbname, $port) {
    $conn = new mysqli($servername, $username, $password, $dbname, $port);
    if ($conn->connect_error) {
        die(json_encode(["success" => false, "message" => "Conexiune eșuată: " . $conn->connect_error]));
    }
    return $conn;
}

$conn = getDbConnection($servername, $username, $password, $dbname, $port);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // 1. AFISARE LISTA STUDENTI (CU GET)
    $sql = "SELECT nume, an_studiu, medie FROM studenti ORDER BY id DESC";
    $result = $conn->query($sql);
    
    $studenti = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $studenti[] = $row;
        }
        echo json_encode(["success" => true, "data" => $studenti]);
    } else {
        echo json_encode(["success" => false, "message" => "Eroare la interogare: " . $conn->error]);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. ADAUGARE STUDENT NOU
    $data = json_decode(file_get_contents('php://input'), true);

    $nume = $data['nume'] ?? '';
    $an_studiu = intval($data['an_studiu'] ?? 0);
    $medie = floatval($data['medie'] ?? 0);

    if (empty($nume) || $an_studiu < 1 || $an_studiu > 4 || $medie <= 0 || $medie > 10) {
        echo json_encode(["success" => false, "message" => "Date de intrare invalide."]);
        $conn->close();
        exit;
    }

    $sql = "INSERT INTO studenti (nume, an_studiu, medie) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    //APARENT A FOST NEVOIE SA FAC O VARIABILA medie_str CA AVEAM PROBLEME LA AFISAREA FLOATULUI
    $medie_str = number_format($medie, 2, '.', '');
    
    if ($stmt) {
        $stmt->bind_param("sis", $nume, $an_studiu, $medie_str);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Student adăugat cu succes."]);
        } else {
            echo json_encode(["success" => false, "message" => "Eroare la adăugare: " . $stmt->error]);
        }
        $stmt->close();
    } else {
         echo json_encode(["success" => false, "message" => "Eroare la prepararea interogării: " . $conn->error]);
    }

} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Metodă nepermisă."]);
}

$conn->close();
?>