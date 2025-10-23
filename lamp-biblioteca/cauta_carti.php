<?php
include 'config.php'; 

header('Content-Type: text/html; charset=utf-8');

if ($conn->connect_error) {
    echo "<tr><td colspan='7' style='text-align: center; color: red;'>Eroare la conexiunea cu baza de date: " . $conn->connect_error . "</td></tr>";
    exit;
}

$search_term = isset($_GET['q']) ? trim($_GET['q']) : '';

$where_clause = '';
if (!empty($search_term)) {
    $search_param = "%" . $search_term . "%";
    
    $where_clause = " WHERE TitluCarte LIKE ? OR AutorCarte LIKE ? OR Categorie LIKE ? ";
}

$sql = "SELECT IDcarte, TitluCarte, AutorCarte, NrExemplare, TipFormat, Categorie, AnAparitie 
        FROM CARTE " 
        . $where_clause .
        " ORDER BY TitluCarte ASC";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo "<tr><td colspan='7' style='text-align: center; color: red;'>Eroare la pregătirea interogării: " . $conn->error . "</td></tr>";
    $conn->close();
    exit;
}

if (!empty($search_term)) {
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}

$stmt->execute();
$result = $stmt->get_result();

$output = '';

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clasa_stoc = (($row["NrExemplare"] ?? 0) <= 5) ? 'stoc-redus' : '';
        
        $output .= "<tr class='$clasa_stoc'>";
        $output .= "<td>" . htmlspecialchars($row["IDcarte"] ?? '') . "</td>";
        $output .= "<td>" . htmlspecialchars($row["TitluCarte"] ?? '') . "</td>";
        $output .= "<td>" . htmlspecialchars($row["AutorCarte"] ?? '') . "</td>";
        $output .= "<td>" . htmlspecialchars($row["Categorie"] ?? '') . "</td>"; 
        $output .= "<td>" . htmlspecialchars($row["TipFormat"] ?? '') . "</td>"; 
        $output .= "<td>" . htmlspecialchars($row["AnAparitie"] ?? '') . "</td>"; 
        $output .= "<td>" . htmlspecialchars($row["NrExemplare"] ?? '') . "</td>"; 
        $output .= "</tr>";
    }
} else {
    $output = "<tr><td colspan='7' style='text-align: center; color: #777;'>
                Nu au fost găsite cărți care să se potrivească cu termenul de căutare.
            </td></tr>";
}

$stmt->close();
$conn->close();

echo $output;
?>