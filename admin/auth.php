<?php
// Admin authentication middleware
require_once __DIR__ . '/../utils/session.php';
require_once __DIR__ . '/../config/db.php';

function isAdminUser()
{
    if (!isset($_SESSION['usuID'])) return false;

    $usuID = (int) $_SESSION['usuID'];

    // First, try admin_users table
    try {
        $database = new Database();
        $conn = $database->getConnection();
        $stmt = $conn->prepare('SELECT 1 FROM admin_users WHERE usuID = :usuID LIMIT 1');
        $stmt->bindParam(':usuID', $usuID, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch(PDO::FETCH_ASSOC)) return true;
    } catch (Exception $e) {
        // Table might not exist; fallback to config
    }

    // Fallback: check config/admins.php for admin emails
    $adminList = [];
    $configFile = __DIR__ . '/../config/admins.php';
    if (file_exists($configFile)) {
        $adminList = include $configFile;
    }

    if (!empty($adminList)) {
        // fetch user email
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $stmt = $conn->prepare('SELECT usuEmail FROM usuarios WHERE usuID = :usuID LIMIT 1');
            $stmt->bindParam(':usuID', $usuID, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && in_array($row['usuEmail'], $adminList)) return true;
        } catch (Exception $e) {
            // can't verify
        }
    }

    return false;
}

function requireAdmin()
{
    if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
        header("Location: ../login.php");
        exit;
    }
}
