<?php

require_once "config/db.php";
require_once "model/Loja.php"; 


$db = new Database();
$conn = $db->getConnection();

$lojaModel = new Loja($conn);

try {
    // Read possible filters from GET
    $filters = [];
    if (isset($_GET['q']) && $_GET['q'] !== '') $filters['q'] = trim($_GET['q']);
    if (isset($_GET['minProducts']) && $_GET['minProducts'] !== '') $filters['minProducts'] = (int)$_GET['minProducts'];
    if (isset($_GET['sort']) && $_GET['sort'] !== '') $filters['sort'] = $_GET['sort'];

    // If any filter provided, use filter() otherwise keep original readAll
    if (!empty($filters)) {
        $lojas = $lojaModel->filter($filters);
    } else {
        $stmt = $lojaModel->readAll();
        $lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

} catch (PDOException $error) {
    echo "Erro na consulta: " . $error->getMessage();
    $lojas = []; 
}
