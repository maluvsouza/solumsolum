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

<div class="container">
    <h1>Gerenciar Usuários</h1>
    <p>Marque/Desmarque admin para conceder ou revogar acesso ao painel.</p>

    <table class="table">
        <thead>
            <tr><th>ID</th><th>Nome</th><th>Email</th><th>Admin</th></tr>
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

<?php require_once __DIR__ . '/../utils/footer.php'; ?>
<style>
.container{max-width:1100px;margin:40px auto;padding:20px}
</style>