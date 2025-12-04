<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$database = new Database();
$conn = $database->getConnection();

// Filtro por status
$filter = $_GET['filter'] ?? 'pending';
$validFilters = ['pending', 'approved', 'rejected', 'all'];
if (!in_array($filter, $validFilters)) $filter = 'pending';

// Fetch requests
$sql = 'SELECT * FROM approval_requests';
if ($filter !== 'all') {
    $sql .= ' WHERE status = :status';
}
$sql .= ' ORDER BY created_at DESC';

$stmt = $conn->prepare($sql);
if ($filter !== 'all') {
    $stmt->bindParam(':status', $filter);
}
$stmt->execute();
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<?php require_once __DIR__ . '/../utils/header.php'; ?>
<link rel="stylesheet" href="adm.css">

<div class="d-flex" style="min-height: 100vh;">
    <!-- Sidebar -->
    <nav class="sidebar" style="width: 250px;">
        <div class="sidebar-brand" style="padding: 0 20px 18px 20px; text-align: center;">
            <a href="../dashboard.php"><img src="../assets/imagens/logo-solum.png" alt="Solum" style="max-width:160px; display:block; margin:auto;"></a>
        </div>
        <ul class="sidebar-nav">
            <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="requests.php" class="active"><i class="bi bi-clipboard-check"></i> Solicitações</a></li>
            <li><a href="stores.php"><i class="bi bi-shop"></i> Lojas</a></li>
            <li><a href="products.php"><i class="bi bi-box"></i> Produtos</a></li>
            <li><a href="users.php"><i class="bi bi-people"></i> Usuários</a></li>
            <li><a href="../dashboard.php"><i class="bi bi-house"></i> Voltar ao Site</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content" style="flex: 1;">
        <div class="page-header mb-20">
            <h1>📋 Solicitações de Aprovação</h1>
            <p>Revise e aprove/rejeite solicitações de vendedores</p>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
            <a href="?filter=pending" class="btn <?php echo ($filter === 'pending' ? 'btn-primary' : 'btn-secondary'); ?>" style="padding: 10px 16px; text-decoration: none;">⏳ Pendentes</a>
            <a href="?filter=approved" class="btn btn-secondary" style="padding: 10px 16px; text-decoration: none;">✓ Aprovadas</a>
            <a href="?filter=rejected" class="btn btn-secondary" style="padding: 10px 16px; text-decoration: none;">✗ Rejeitadas</a>
            <a href="?filter=all" class="btn btn-secondary" style="padding: 10px 16px; text-decoration: none;">Todas</a>
        </div>

        <?php if (empty($requests)): ?>
            <div style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 64px; margin-bottom: 20px;">📭</div>
                <p style="font-size: 18px; color: var(--text-light);">Nenhuma solicitação encontrada</p>
            </div>
        <?php else: ?>
            <div style="display: grid; gap: 20px;">
                <?php foreach ($requests as $req): ?>
                    <?php
                    $data = json_decode($req['data_json'], true);
                    $type_label = $req['type'] === 'store' ? '🏪 Loja' : '📦 Produto';
                    $title = $data[$req['type'] === 'store' ? 'lojNome' : 'proNome'] ?? '?';
                    ?>
                    <div class="table-container" style="border-left: 5px solid <?php 
                        echo $req['status'] === 'pending' ? 'var(--warning-color)' : 
                            ($req['status'] === 'approved' ? 'var(--success-color)' : 'var(--danger-color)'); 
                    ?>">
                        <div style="padding: 20px;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                <div>
                                    <h4 style="margin: 0 0 5px 0; font-size: 18px;"><?php echo $type_label . ': ' . htmlspecialchars($title); ?></h4>
                                    <small style="color: var(--text-light);">ID #<?php echo $req['id']; ?> • <?php echo date('d/m/Y H:i', strtotime($req['created_at'])); ?></small>
                                </div>
                                <span class="badge badge-<?php echo $req['status']; ?>">
                                    <?php 
                                        echo match($req['status']) {
                                            'pending' => '⏳ Aguardando',
                                            'approved' => '✓ Aprovado',
                                            'rejected' => '✗ Rejeitado',
                                            default => ucfirst($req['status'])
                                        };
                                    ?>
                                </span>
                            </div>

                            <div style="background: var(--light-bg); padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                                    <div>
                                        <small style="color: var(--text-light); font-weight: 600;">Vendedor</small>
                                        <p style="margin: 5px 0 0 0;"><?php echo htmlspecialchars($req['vendedor_nome']); ?></p>
                                        <small style="color: var(--text-light);"><?php echo htmlspecialchars($req['vendedor_email']); ?></small>
                                    </div>
                                </div>
                            </div>

                            <?php if ($req['type'] === 'store'): ?>
                                <h5 style="margin-top: 15px; margin-bottom: 10px; font-weight: 600;">Detalhes da Loja</h5>
                                <div style="background: white; padding: 15px; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 15px;">
                                    <p style="margin: 0 0 8px 0;"><strong>Nome:</strong> <?php echo htmlspecialchars($data['lojNome']); ?></p>
                                    <p style="margin: 0 0 8px 0;"><strong>Descrição:</strong></p>
                                    <p style="margin: 0 0 10px 0; color: var(--text-light);"><?php echo nl2br(htmlspecialchars($data['lojDescricao'])); ?></p>
                                    <?php if (!empty($data['lojLogo'])): ?>
                                        <div style="margin-top: 10px;">
                                            <strong>Logo:</strong><br>
                                            <img src="<?php echo htmlspecialchars($data['lojLogo']); ?>" alt="Logo" style="max-width: 200px; max-height: 150px; margin-top: 8px; border-radius: 6px; border: 1px solid var(--border-color);">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($req['type'] === 'product'): ?>
                                <h5 style="margin-top: 15px; margin-bottom: 10px; font-weight: 600;">Detalhes do Produto</h5>
                                <div style="background: white; padding: 15px; border: 1px solid var(--border-color); border-radius: 8px; margin-bottom: 15px;">
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 15px;">
                                        <div>
                                            <strong>Nome:</strong>
                                            <p style="margin: 5px 0 0 0; color: var(--text-dark);"><?php echo htmlspecialchars($data['proNome']); ?></p>
                                        </div>
                                        <div>
                                            <strong>Preço:</strong>
                                            <p style="margin: 5px 0 0 0; color: var(--success-color); font-weight: 600;">R$ <?php echo number_format($data['proPreco'], 2, ',', '.'); ?></p>
                                        </div>
                                        <div>
                                            <strong>Quantidade:</strong>
                                            <p style="margin: 5px 0 0 0; color: var(--text-dark);"><?php echo (int)$data['proQuantidadeEstoque']; ?> un.</p>
                                        </div>
                                        <div>
                                            <strong>Categoria:</strong>
                                            <p style="margin: 5px 0 0 0; color: var(--text-dark);"><?php echo htmlspecialchars($data['catNome'] ?? 'N/A'); ?></p>
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 15px;">
                                        <strong>Descrição:</strong>
                                        <p style="margin: 5px 0 0 0; color: var(--text-light);"><?php echo nl2br(htmlspecialchars($data['proDescricao'])); ?></p>
                                    </div>
                                    <?php 
                                    $images = array_filter([
                                        $data['proFoto'] ?? null,
                                        $data['proFoto2'] ?? null,
                                        $data['proFoto3'] ?? null
                                    ]);
                                    if (!empty($images)): 
                                    ?>
                                        <div>
                                            <strong>Imagens:</strong>
                                            <div style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;">
                                                <?php foreach ($images as $img): ?>
                                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="Produto" style="max-width: 120px; max-height: 120px; border-radius: 6px; border: 1px solid var(--border-color);">
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($req['admin_note'])): ?>
                                <div style="background: rgba(245, 158, 11, 0.08); border-left: 3px solid var(--warning-color); padding: 12px; border-radius: 6px; margin-bottom: 15px;">
                                    <strong style="color: var(--warning-color);">📝 Nota do Admin:</strong>
                                    <p style="margin: 5px 0 0 0;"><?php echo nl2br(htmlspecialchars($req['admin_note'])); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($req['status'] === 'pending'): ?>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                                    <form method="post" action="process_request.php">
                                        <input type="hidden" name="request_id" value="<?php echo (int)$req['id']; ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <textarea name="note" placeholder="Nota opcional..." rows="3" class="form-control" style="margin-bottom: 10px;"></textarea>
                                        <button type="submit" class="btn btn-success" style="width: 100%;">✓ Aprovar Solicitação</button>
                                    </form>
                                    <form method="post" action="process_request.php">
                                        <input type="hidden" name="request_id" value="<?php echo (int)$req['id']; ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <textarea name="note" placeholder="Motivo da rejeição..." rows="3" class="form-control" style="margin-bottom: 10px;"></textarea>
                                        <button type="submit" class="btn btn-danger" style="width: 100%;">✗ Rejeitar Solicitação</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../utils/footer.php'; ?>
