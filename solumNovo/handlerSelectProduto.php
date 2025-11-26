<?php

require_once "config/db.php";
require_once "model/Produto.php";

// Conexão com BD
$database = new Database();
$db = $database->getConnection();


$proID = $_GET['proID'] ?? null;

if ($proID) {
    $query = "SELECT * FROM produtos WHERE proID = :proID";
    $stmt = $db->prepare($query);
    $stmt->execute(['proID' => $proID]); 
    $produto = $stmt->fetch();

    if ($produto) {
        $proID = $produto['proID'];
        $proNome = $produto['proNome'];
        $proPreco = $produto['proPreco'];
        $proDescricao = $produto['proDescricao'];
        $proLojaID = $produto['proLojaID'];
        // $proVenLoja = $produto['proVenLoja'];
    }
}


?>