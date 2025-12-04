<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$database = new Database();
$conn = $database->getConnection();

// Simple stats
$userCount = 0;
$productCount = 0;
$storeCount = 0;
$pendingCount = 0;

try {
    $stmt = $conn->query('SELECT COUNT(*) as c FROM usuarios');
    $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

    $stmt = $conn->query('SELECT COUNT(*) as c FROM produtos');
    $productCount = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

    $stmt = $conn->query('SELECT COUNT(*) as c FROM lojas');
    $storeCount = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

    $stmt = $conn->query('SELECT COUNT(*) as c FROM approval_requests WHERE status = "pending"');
    $pendingCount = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;
} catch (Exception $e) {
    // ignore
}

?>
<?php require_once __DIR__ . '/../utils/header.php'; ?>
<link rel="stylesheet" href="adm.css">

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <a href="../admin/dashboard.php">
                <img src="../assets/logo/logo-solum.png" alt="Solum">
            </a>
        </div>
        <ul class="sidebar-nav">
            <li><a href="dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
            <li><a href="requests.php"><i class="bi bi-clipboard-check"></i> Solicitações</a></li>
            <li><a href="stores.php"><i class="bi bi-shop"></i> Lojas</a></li>
            <li><a href="products.php"><i class="bi bi-box"></i> Produtos</a></li>
            <li><a href="users.php"><i class="bi bi-people"></i> Usuários</a></li>
            <li><a href="../index.php"><i class="bi bi-house"></i> Voltar ao Site</a></li>
            <li><a href="logout.php"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content">


        <div class="page-header mb-20">
            <h1>📊 Dashboard</h1>
            <p>Bem-vindo ao painel administrativo do Solum</p>
        </div>

        <div class="infos-dashboard">

            <div class="stats-grid">
                <div class="stat-card pending">
                    <div class="stat-icon">📋</div>
                    <div class="stat-value"><?php echo (int)$pendingCount; ?></div>
                    <div class="stat-label">Solicitações Pendentes</div>
                </div>

                <div class="stat-card usuario">
                    <div class="stat-icon">👥</div>
                    <div class="stat-value"><?php echo (int)$userCount; ?></div>
                    <div class="stat-label">Usuários Totais</div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">🏪</div>
                    <div class="stat-value"><?php echo (int)$storeCount; ?></div>
                    <div class="stat-label">Lojas Ativas</div>
                </div>

                <div class="stat-card product">
                    <div class="stat-icon">📦</div>
                    <div class="stat-value"><?php echo (int)$productCount; ?></div>
                    <div class="stat-label">Produtos Totais</div>
                </div>
            </div>

            <div class="off-table">
                <div class="table-container">
                    <h5 style="padding: 20px; border-bottom: 1px solid var(--border-color); margin: 0; font-weight: 600;">⚡ Ações Rápidas</h5>
                    <div style="padding: 20px;">
                        <a href="requests.php" class="btn btn-primary">Revisar Solicitações</a>
                        <a href="stores.php" class="btn btn-secondary">Gerenciar Lojas</a>
                        <a href="products.php" class="btn btn-secondary">Gerenciar Produtos</a>
                        <a href="users.php" class="btn btn-secondary">Gerenciar Usuários</a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>