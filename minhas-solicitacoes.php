<?php
// Configurações de sessão
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'solum.hubsapiens.com.br',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// Banco e header
require_once "config/db.php";
require_once "./utils/header.php";

// Verifica login
if (!isset($_SESSION['usuID'])) {
    echo "<script>
        alert('Você precisa estar logado para acessar esta página.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

// Navbar (somente logado entra aqui)
require_once "./utils/navbar_logado.php";

// Conexão
$database = new Database();
$conn = $database->getConnection();

// Verifica se usuário é vendedor
$getVendQuery = "SELECT vendID FROM vendedores WHERE vendUsuID = :usuID";
$getVendStmt = $conn->prepare($getVendQuery);
$getVendStmt->bindParam(':usuID', $_SESSION['usuID'], PDO::PARAM_INT);
$getVendStmt->execute();
$vendResult = $getVendStmt->fetch(PDO::FETCH_ASSOC);

// Se não for vendedor → mandar criar loja
if (!$vendResult) {
    header('Location: pag-vender.php');
    exit;
}

$vendID = $vendResult['vendID'];

// Buscar solicitações
$query = "SELECT * FROM approval_requests WHERE vendID = :vendID ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Detectar nova solicitação
$isNovo = isset($_GET['novo']) && $_GET['novo'] === '1';
$tipoNovo = $_GET['tipo'] ?? 'loja';
?>

<?php if ($isNovo): ?>
<script>
alert('✓ Solicitação enviada com sucesso!\n\nSua <?= $tipoNovo === "product" ? "produto" : "loja" ?> foi enviada para análise.');
</script>
<?php endif; ?>

<div class="container-main">

<!-- TÍTULO -->
    <h2 class="mb-4">📋 Minhas Solicitações</h2>

    <!-- CASO NAO HAJA SOLICITAÇÕES -->

    <?php if (count($requests) === 0): ?>
        <div class="no-requests">
            <div class="no-requests-icon">📭</div>
            <p>Você não tem nenhuma solicitação ainda.</p>
            <p><small>Quando você criar uma loja ou produto, elas aparecerão aqui.</small></p>
        </div>
    <?php else: ?>

        <!-- CASO HAJA SOLICITAÇÕES -->
        <?php foreach ($requests as $request): ?>
            <?php $data = json_decode($request['data_json'], true); ?>

            <div class="request-card <?= $request['status']; ?>">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="type-badge type-<?= $request['type'] === 'store' ? 'store' : 'product'; ?>">
                            <?= $request['type'] === 'store' ? '🏪 Loja' : '📦 Produto'; ?>
                        </span>

                        <span class="status-badge status-<?= $request['status']; ?>">
                            <?= match ($request['status']) {
                                'pending' => '⏳ Aguardando',
                                'approved' => '✓ Aprovado',
                                'rejected' => '✗ Rejeitado',
                                default => ucfirst($request['status'])
                            }; ?>
                        </span>
                    </div>

                    <small class="text-muted">ID: #<?= $request['id']; ?></small>
                </div>

                <h5 class="tipo-titulo mt-3 mb-2">
                    <?= htmlspecialchars($data[$request['type'] === 'store' ? 'lojNome' : 'proNome']); ?>
                </h5>

                <?php if ($request['type'] === 'store'): ?>
                    <p class="info-tipo mb-1"><strong>Descrição:</strong> <?= htmlspecialchars($data['lojDescricao']); ?></p>
                <?php else: ?>
                    <p class="info-tipo mb-1"><strong>Loja:</strong> <?= htmlspecialchars($data['catNome']); ?></p>
                    <p class="info-tipo mb-1"><strong>Preço:</strong> R$ <?= number_format($data['proPreco'], 2, ',', '.'); ?></p>
                    <p class="info-tipo mb-1"><strong>Descrição:</strong> <?= htmlspecialchars($data['proDescricao']); ?></p>
                <?php endif; ?>

                <?php if (!empty($request['admin_note'])): ?>
                    <div class="admin-note">
                        <strong>📝 Observação do admin:</strong><br>
                        <?= htmlspecialchars($request['admin_note']); ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once "./utils/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/mobile-nav.js"></script>
