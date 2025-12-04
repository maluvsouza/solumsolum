<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$database = new Database();
$conn = $database->getConnection();

// Fetch users
$stmt = $conn->prepare('SELECT usuID, usuNome, usuEmail FROM usuarios ORDER BY usuID DESC');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// fetch admin mappings
$adminMap = [];
try {
    $stmt2 = $conn->query('SELECT usuID FROM admin_users');
    $rows = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) $adminMap[(int)$r['usuID']] = true;
} catch (Exception $e) {
    // table may not exist
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

    <div class="main-content">

        <div class="page-header mb-20">
            <h1>Gerenciar Usuários</h1>
            <p>Marque/Desmarque admin para conceder ou revogar acesso ao painel.</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['usuID']); ?></td>
                            <td><?php echo htmlspecialchars($u['usuNome']); ?></td>
                            <td><?php echo htmlspecialchars($u['usuEmail']); ?></td>
                            <td>
                                <form method="post" action="toggle_admin.php" style="display:inline">
                                    <input type="hidden" name="usuID" value="<?php echo (int)$u['usuID']; ?>">
                                    <?php if (isset($adminMap[(int)$u['usuID']])): ?>
                                        <input type="hidden" name="action" value="revoke">
                                        <button class="btn btn-danger" type="submit">Revogar admin</button>
                                    <?php else: ?>
                                        <input type="hidden" name="action" value="grant">
                                        <button class="btn btn-success" type="submit">Conceder admin</button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>