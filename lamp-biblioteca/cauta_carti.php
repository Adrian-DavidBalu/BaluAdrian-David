<?php
include 'config.php';

header('Content-Type: application/json');

$searchTerm = isset($_GET['q']) ? trim($_GET['q']) : '';
$books = [];

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Eroare la conexiunea cu baza de date: " . $conn->connect_error]);
    exit();
}

$where_clause = '';
if (!empty($searchTerm)) {
    $search_param = "%" . $searchTerm . "%";
    
    $where_clause = " WHERE TitluCarte LIKE ? OR AutorCarte LIKE ? OR Categorie LIKE ? ";
}

$sql = "SELECT IDCarte, TitluCarte, AutorCarte, NrExemplare, TipFormat, Categorie, AnAparitie 
        FROM CARTE " 
        . $where_clause .
        " ORDER BY TitluCarte ASC";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["error" => "Eroare la pregătirea interogării: " . $conn->error]);
    $conn->close();
    exit();
}

if (!empty($searchTerm)) {
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $books[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($books);
?>