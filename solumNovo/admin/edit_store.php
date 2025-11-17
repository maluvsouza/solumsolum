<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: stores.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$stmt = $conn->prepare('SELECT * FROM lojas WHERE lojID = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$store = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$store) {
    echo "Loja não encontrada.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['lojNome'] ?? $store['lojNome'];
    $descricao = $_POST['lojDescricao'] ?? $store['lojDescricao'];
    $logo = $store['lojLogo'];

    // TODO: Handle logo upload if needed

    $stmt2 = $conn->prepare('UPDATE lojas SET lojNome = :nome, lojDescricao = :descricao WHERE lojID = :id');
    $stmt2->bindParam(':nome', $nome);
    $stmt2->bindParam(':descricao', $descricao);
    $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();

    header('Location: stores.php?success=1');
    exit;
}

?>
<?php require_once __DIR__ . '/../utils/header.php'; ?>

<div class="admin-container">
    <h1>Editar Loja</h1>

    <form method="post" style="max-width: 600px;">
        <div class="form-group">
            <label>Nome:</label>
            <input type="text" name="lojNome" value="<?php echo htmlspecialchars($store['lojNome']); ?>" required>
        </div>

        <div class="form-group">
            <label>Descrição:</label>
            <textarea name="lojDescricao" required><?php echo htmlspecialchars($store['lojDescricao']); ?></textarea>
        </div>

        <?php if (!empty($store['lojLogo'])): ?>
            <div class="form-group">
                <label>Logo Atual:</label><br>
                <img src="<?php echo htmlspecialchars($store['lojLogo']); ?>" style="height:100px">
            </div>
        <?php endif; ?>

        <button type="submit" class="btn-success">Salvar</button>
        <a href="stores.php" class="btn-secondary">Cancelar</a>
    </form>

</div>

<?php require_once __DIR__ . '/../utils/footer.php'; ?>

<style>
.admin-container { max-width: 1200px; margin: 40px auto; padding: 20px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
.form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; }
.btn-success { background: #10b981; color: white; padding: 10px 16px; border: none; cursor: pointer; }
.btn-secondary { background: #999; color: white; padding: 10px 16px; text-decoration: none; }
</style>
