<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$database = new Database();
$conn = $database->getConnection();

// Fetch stores
$stmt = $conn->query('SELECT l.lojID, l.lojNome, l.lojDescricao, l.lojLogo, l.lojVendedorID, u.usuNome, u.usuEmail 
                       FROM lojas l 
                       LEFT JOIN vendedores v ON l.lojVendedorID = v.vendID
                       LEFT JOIN usuarios u ON v.vendUsuID = u.usuID
                       ORDER BY l.lojID DESC');
$stores = $stmt->fetchAll(PDO::FETCH_ASSOC);

$success = isset($_GET['deleted']) && $_GET['deleted'] === '1';
$error = isset($_GET['error']) ? $_GET['error'] : null;

?>
<?php require_once __DIR__ . '/../utils/header.php'; ?>
<link rel="stylesheet" href="styles.css">

<div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <nav class="sidebar" style="width: 250px;">
        <div class="sidebar-brand" style="padding: 0 20px 18px 20px; text-align: center;">
            <a href="../index.php"><img src="../assets/imagens/logo-solum.png" alt="Solum" style="max-width:160px; display:block; margin:auto;"></a>
        </div>
        <ul class="sidebar-nav">
            <li><a href="index.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="requests.php"><i class="bi bi-clipboard-check"></i> Solicitações</a></li>
            <li><a href="stores.php" class="active"><i class="bi bi-shop"></i> Lojas</a></li>
            <li><a href="products.php"><i class="bi bi-box"></i> Produtos</a></li>
            <li><a href="users.php"><i class="bi bi-people"></i> Usuários</a></li>
            <li><a href="../index.php"><i class="bi bi-house"></i> Voltar ao Site</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content" style="flex: 1;">
        <div class="page-header mb-20">
            <h1>🏪 Gerenciar Lojas</h1>
            <p>Total: <strong><?php echo count($stores); ?></strong> loja<?php echo count($stores) !== 1 ? 's' : ''; ?></p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <span>✓ Loja removida com sucesso!</span>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <span>✗ Erro: <?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <?php if (empty($stores)): ?>
                <div style="padding: 40px; text-align: center; color: var(--text-light);">
                    <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                    <p>Nenhuma loja cadastrada ainda</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nome da Loja</th>
                            <th>Descrição</th>
                            <th>Vendedor</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stores as $s): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($s['lojLogo'])): ?>
                                        <img src="../<?php echo htmlspecialchars($s['lojLogo']); ?>" alt="Logo">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($s['lojNome']); ?></strong></td>
                                <td>
                                    <small class="text-muted"><?php echo htmlspecialchars(substr($s['lojDescricao'], 0, 50)) . (strlen($s['lojDescricao']) > 50 ? '...' : ''); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($s['usuNome'] ?? 'N/A'); ?></td>
                                <td>
                                    <small><?php echo htmlspecialchars($s['usuEmail'] ?? 'N/A'); ?></small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_store.php?id=<?php echo (int)$s['lojID']; ?>" class="btn btn-sm btn-secondary">Editar</a>
                                        <a href="delete_store.php?id=<?php echo (int)$s['lojID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza? Todos os produtos relacionados serão removidos!')">Remover</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../utils/footer.php'; ?>
