<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost","user","pass","db");
$sql = "SELECT * FROM registros ORDER BY data DESC";
$result = $conn->query($sql);

$dados = [];
while ($row = $result->fetch_assoc()) {
    $dados[] = $row;
}

echo json_encode($dados);
