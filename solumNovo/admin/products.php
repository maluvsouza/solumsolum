<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$database = new Database();
$conn = $database->getConnection();

$products = [];
try {
    $stmt = $conn->query('SELECT p.proID, p.proNome, p.proPreco, p.proFoto, p.proLojaID, l.lojNome 
                           FROM produtos p 
                           LEFT JOIN lojas l ON p.proLojaID = l.lojID
                           ORDER BY p.proID DESC');
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // ignore
}

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
            <li><a href="stores.php"><i class="bi bi-shop"></i> Lojas</a></li>
            <li><a href="products.php" class="active"><i class="bi bi-box"></i> Produtos</a></li>
            <li><a href="users.php"><i class="bi bi-people"></i> Usuários</a></li>
            <li><a href="../index.php"><i class="bi bi-house"></i> Voltar ao Site</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content" style="flex: 1;">
        <div class="page-header mb-20">
            <h1>📦 Gerenciar Produtos</h1>
            <p>Total: <strong><?php echo count($products); ?></strong> produto<?php echo count($products) !== 1 ? 's' : ''; ?></p>
        </div>

        <div class="table-container">
            <?php if (empty($products)): ?>
                <div style="padding: 40px; text-align: center; color: var(--text-light);">
                    <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                    <p>Nenhum produto cadastrado ainda</p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Imagem</th>
                            <th>Nome do Produto</th>
                            <th>Preço</th>
                            <th>Loja</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($p['proFoto'])): ?>
                                        <img src="../<?php echo htmlspecialchars($p['proFoto']); ?>" alt="Produto">
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($p['proNome']); ?></strong></td>
                                <td><span style="background: rgba(16, 185, 129, 0.1); padding: 4px 8px; border-radius: 4px; color: var(--success-color); font-weight: 600;">R$ <?php echo number_format($p['proPreco'], 2, ',', '.'); ?></span></td>
                                <td><?php echo htmlspecialchars($p['lojNome'] ?? 'N/A'); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_product.php?id=<?php echo (int)$p['proID']; ?>" class="btn btn-sm btn-secondary">Editar</a>
                                        <a href="delete_product.php?id=<?php echo (int)$p['proID']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Remover</a>
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