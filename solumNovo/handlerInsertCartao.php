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

$numero = isset($_POST['numero']) ? preg_replace('/\D/', '', $_POST['numero']) : '';
$nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$exp_month = isset($_POST['exp_month']) ? trim($_POST['exp_month']) : '';
$exp_year = isset($_POST['exp_year']) ? trim($_POST['exp_year']) : '';
// cvv não será armazenado

if (strlen($numero) < 12 || empty($nome) || empty($exp_month) || empty($exp_year)) {
    header('Location: cadastrar-pagamento.php?msg=' . urlencode('Dados inválidos'));
    exit;
}

$last4 = substr($numero, -4);

function detectBrand($num) {
    if (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $num)) return 'Visa';
    if (preg_match('/^5[1-5][0-9]{14}$/', $num)) return 'Mastercard';
    if (preg_match('/^3[47][0-9]{13}$/', $num)) return 'AMEX';
    if (preg_match('/^6(?:011|5[0-9]{2})[0-9]{12}$/', $num)) return 'Discover';
    return 'Outro';
}

$brand = detectBrand($numero);

// token simples: hash (não use em produção sem provider apropriado)
$token = hash('sha256', $numero . time());

$database = new Database();
$db = $database->getConnection();

// cria tabela se nao existir
$createSql = "CREATE TABLE IF NOT EXISTS cartoes (
  cartaoID INT AUTO_INCREMENT PRIMARY KEY,
  usuID INT NOT NULL,
  last4 VARCHAR(4) NOT NULL,
  brand VARCHAR(50),
  nome VARCHAR(255),
  exp_month VARCHAR(2),
  exp_year VARCHAR(4),
  token VARCHAR(255),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";
try {
    $db->exec($createSql);
} catch (Exception $e) {}

try {
    $stmt = $db->prepare('INSERT INTO cartoes (usuID, last4, brand, nome, exp_month, exp_year, token) VALUES (:idu, :last4, :brand, :nome, :exp_month, :exp_year, :token)');
    $stmt->bindParam(':idu', $usuID);
    $stmt->bindParam(':last4', $last4);
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':exp_month', $exp_month);
    $stmt->bindParam(':exp_year', $exp_year);
    $stmt->bindParam(':token', $token);
    $stmt->execute();

    header('Location: cadastrar-pagamento.php?msg=' . urlencode('Cartão salvo com sucesso'));
    exit;
} catch (Exception $e) {
    header('Location: cadastrar-pagamento.php?msg=' . urlencode('Erro ao salvar cartão'));
    exit;
}

?>
