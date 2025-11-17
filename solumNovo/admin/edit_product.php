<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    header('Location: products.php');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

$stmt = $conn->prepare('SELECT * FROM produtos WHERE proID = :id');
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Produto não encontrado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['proNome'] ?? $product['proNome'];
    $descricao = $_POST['proDescricao'] ?? $product['proDescricao'];
    $preco = $_POST['proPreco'] ?? $product['proPreco'];
    $quant = $_POST['proQuantidadeEstoque'] ?? $product['proQuantidadeEstoque'];

    $stmt2 = $conn->prepare('UPDATE produtos SET proNome = :nome, proDescricao = :desc, proPreco = :preco, proQuantidadeEstoque = :quant WHERE proID = :id');
    $stmt2->bindParam(':nome', $nome);
    $stmt2->bindParam(':desc', $descricao);
    $stmt2->bindParam(':preco', $preco);
    $stmt2->bindParam(':quant', $quant, PDO::PARAM_INT);
    $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt2->execute();

    header('Location: products.php?success=1');
    exit;
}

?>
<?php require_once __DIR__ . '/../utils/header.php'; ?>

<div class="admin-container">
    <h1>Editar Produto</h1>

    <form method="post" style="max-width: 600px;">
        <div class="form-group">
            <label>Nome:</label>
            <input type="text" name="proNome" value="<?php echo htmlspecialchars($product['proNome']); ?>" required>
        </div>

        <div class="form-group">
            <label>Descrição:</label>
            <textarea name="proDescricao" required><?php echo htmlspecialchars($product['proDescricao']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Preço:</label>
            <input type="number" name="proPreco" value="<?php echo htmlspecialchars($product['proPreco']); ?>" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Quantidade:</label>
            <input type="number" name="proQuantidadeEstoque" value="<?php echo (int)$product['proQuantidadeEstoque']; ?>" required>
        </div>

        <button type="submit" class="btn-success">Salvar</button>
        <a href="products.php" class="btn-secondary">Cancelar</a>
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
