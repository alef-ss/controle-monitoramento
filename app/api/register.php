<?php
require '../src/db/db.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $carro = $_POST["carro"];
    $data = $_POST["data"];
    $gravacao = (int)$_POST["gravacao"];
    $trocado = (int)$_POST["trocado"];

    $sql = "INSERT INTO registros (carro, data_registro, gravacao, trocado) VALUES (?,?,?,?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die(mysqli_error($conn));
    }

    $stmt->bind_param("ssii", $carro, $data, $gravacao, $trocado);
    $stmt->execute();
    
    header("Location: ../pages/home.php");

    $stmt->close();
    $conn->close();
}
