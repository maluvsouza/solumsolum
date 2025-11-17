<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: stores.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

try {
    // Primeiro, deletar todos os produtos associados a essa loja
    $stmt1 = $conn->prepare('DELETE FROM produtos WHERE proLojaID = :id');
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();

    // Depois, deletar a loja
    $stmt2 = $conn->prepare('DELETE FROM lojas WHERE lojID = :id');
    $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();

    header('Location: stores.php?deleted=1');
    exit;
} catch (Exception $e) {
    header('Location: stores.php?error=' . urlencode($e->getMessage()));
    exit;
}
