<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: products.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

try {
    $stmt = $conn->prepare('DELETE FROM produtos WHERE proID = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: products.php?deleted=1');
    exit;
} catch (Exception $e) {
    header('Location: products.php?error=' . urlencode($e->getMessage()));
    exit;
}
