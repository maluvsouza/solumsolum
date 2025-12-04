<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();

require_once __DIR__ . '/config/db.php';

// exige login
if (!isset($_SESSION['usuID'])) {
    header('Location: login.php');
    exit;
}

$usuID = $_SESSION['usuID'];

$database = new Database();
$db = $database->getConnection();

// cria tabela se nao existir (camada simples, pode ser removida se preferir migracao separada)
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
} catch (Exception $e) {
    // não interrompe a exibição; apenas log
}

// mensagen via GET
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';

// busca cartões do usuário
$cards = [];
try {
    $stmt = $db->prepare('SELECT * FROM cartoes WHERE usuID = :idu ORDER BY created_at DESC');
    $stmt->bindParam(':idu', $usuID);
    $stmt->execute();
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $msg = 'Erro ao carregar cartões.';
}

?>
<?php require_once __DIR__ . '/utils/header.php'; ?>
<?php require_once __DIR__ . '/utils/navbar_logado.php'; ?>

<div class="payment-container">
    <h2>Meus métodos de pagamento</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>

    <div class="payment-row">
        <div class="cards-column">
            <h4>Cartões salvos</h4>
            <?php if (count($cards) === 0): ?>
                <p>Você ainda não tem cartões salvos.</p>
            <?php else: ?>
                <ul class="cards-list list-group">
                    <?php foreach ($cards as $c): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="card-brand"><?php echo htmlspecialchars($c['brand'] ?? 'Cartão'); ?></span>
                                &mdash; **** **** **** <?php echo htmlspecialchars($c['last4']); ?>
                                <div class="card-meta"><small><?php echo htmlspecialchars($c['nome']); ?> • <?php echo htmlspecialchars($c['exp_month'] . '/' . $c['exp_year']); ?></small></div>
                            </div>
                            <form method="post" action="handlerDeleteCartao.php" onsubmit="return confirm('Excluir este cartão?');">
                                <input type="hidden" name="cartaoID" value="<?php echo (int)$c['cartaoID']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="form-column">
            <h4>Adicionar novo cartão</h4>
            <form class="card-form" method="post" action="handlerInsertCartao.php">
                <div class="mb-3">
                    <label for="numero" class="form-label">Número do cartão</label>
                    <input required type="text" class="form-control" id="numero" name="numero" placeholder="0000 0000 0000 0000">
                </div>
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome (como no cartão)</label>
                    <input required type="text" class="form-control" id="nome" name="nome" placeholder="Nome completo">
                </div>
                <div class="row">
                    <div class="col-5 mb-3">
                        <label for="exp_month" class="form-label">Mês</label>
                        <input required type="text" class="form-control" id="exp_month" name="exp_month" placeholder="MM">
                    </div>
                    <div class="col-5 mb-3">
                        <label for="exp_year" class="form-label">Ano</label>
                        <input required type="text" class="form-control" id="exp_year" name="exp_year" placeholder="YYYY">
                    </div>
                    <div class="col-2 mb-3">
                        <label for="cvv" class="form-label">CVV</label>
                        <input required type="password" class="form-control" id="cvv" name="cvv" placeholder="***">
                    </div>
                </div>
                <small class="muted-note">Observação: por segurança o CVV não será armazenado. Apenas últimos 4 dígitos são salvos.</small>
                <div class="mt-3">
                    <button class="btn btn-primary" type="submit">Salvar cartão</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/utils/footer.php'; ?>

