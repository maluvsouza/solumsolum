<?php
require_once __DIR__ . '/utils/session.php';
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['usuID'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cadastrar-pagamento.php');
    exit;
}

$usuID = $_SESSION['usuID'];
$cartaoID = isset($_POST['cartaoID']) ? (int)$_POST['cartaoID'] : 0;

if ($cartaoID <= 0) {
    header('Location: cadastrar-pagamento.php?msg=' . urlencode('ID inválido'));
    exit;
}

$database = new Database();
$db = $database->getConnection();

try {
    $stmt = $db->prepare('DELETE FROM cartoes WHERE cartaoID = :cid AND usuID = :idu');
    $stmt->bindParam(':cid', $cartaoID);
    $stmt->bindParam(':idu', $usuID);
    $stmt->execute();

    header('Location: cadastrar-pagamento.php?msg=' . urlencode('Cartão excluído'));
    exit;
} catch (Exception $e) {
    header('Location: cadastrar-pagamento.php?msg=' . urlencode('Erro ao excluir cartão'));
    exit;
}

?>
