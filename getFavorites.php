<?php
session_start();
require_once("./config/db.php");
require_once("./model/Produto.php");

$db = new Database();
$conn = $db->getConnection();

if (!isset($_SESSION['favorites']) || empty($_SESSION['favorites'])) {
    echo "<p>Você ainda não adicionou produtos aos favoritos.</p>";
    exit;
}

// Inicializa objeto Produto
$produtoObj = new Produto($conn); // $pdo é sua conexão com o banco

$favoritos = $_SESSION['favorites'];
$produtos = $produtoObj->getFavoritos($favoritos);

function formatPrice($price)
{
    return 'R$ ' . number_format($price, 2, ',', '.');
}

foreach ($produtos as $produto): ?>
    <div class="produto-favorito" data-id="<?= $produto['proID'] ?>">
        <button class="btn-remover-fav" data-remove-id="<?= $produto['proID'] ?>"><i class="fa-solid fa-xmark" style="color: var(--texto);"></i>
        </button>

        <img src="<?= $produto['proFoto'] ?>" alt="">

        <div>
            <strong><?= htmlspecialchars($produto['proNome']) ?></strong><br>
            <span><?= formatPrice($produto['proPreco']) ?></span>
            <a href="produto.php?proID=<?= $produto['proID'] ?>" class="btn btn-sm btn-outline-primary">Ver</a>
        </div>
    </div>

    <div class="favorito-divider"></div>
<?php endforeach; ?>