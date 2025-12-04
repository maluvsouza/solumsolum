<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$usuID = isset($_POST['usuID']) ? (int)$_POST['usuID'] : 0;
action:
$action = isset($_POST['action']) ? $_POST['action'] : '';

$database = new Database();
$conn = $database->getConnection();

try {
    if ($action === 'grant') {
        $stmt = $conn->prepare('INSERT INTO admin_users (usuID) VALUES (:usuID)');
        $stmt->bindParam(':usuID', $usuID, PDO::PARAM_INT);
        $stmt->execute();
    } elseif ($action === 'revoke') {
        $stmt = $conn->prepare('DELETE FROM admin_users WHERE usuID = :usuID');
        $stmt->bindParam(':usuID', $usuID, PDO::PARAM_INT);
        $stmt->execute();
    }
} catch (Exception $e) {
    // If table doesn't exist, show error with link to run setup
    header('Location: users.php?error=' . urlencode($e->getMessage()));
    exit;
}

header('Location: users.php');
exit;
