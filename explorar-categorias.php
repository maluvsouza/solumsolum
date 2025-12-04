<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => 'solum.hubsapiens.com.br',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";
?>
<?php


require_once "config/db.php";
require_once "model/Produto.php";
require_once "model/Categoria.php";


require_once("./utils/header.php");
if (isset($_SESSION['usuID'])) {

    require_once("./utils/navbar_logado.php");
} else {

    require_once("./utils/navbar_nao-logado.php");
}

// helper functions (same as explorar-produtos.php)
function isInFavorites($productId)
{
    return isset($_SESSION['favorites']) && in_array($productId, $_SESSION['favorites']);
}

function formatPrice($price)
{
    return 'R$ ' . number_format($price, 2, ',', '.');
}

// Conexão com o banco de dados
$database = new Database();
$db = $database->getConnection();

// Instanciando os modelos
$produto = new Produto($db);
$categoria = new Categoria($db);

// Pega o catID do GET
if (isset($_GET['catID']) && is_numeric($_GET['catID'])) {
    $catID = intval($_GET['catID']);

    // Busca os produtos da categoria
    $stmt = $produto->readByCategory($catID);
    $num = $stmt->rowCount();

    // Busca o nome da categoria
    $categoriaInfo = $categoria->getCategoryByID($catID);
    if ($categoriaInfo) {
        $catNome = $categoriaInfo['catNome']; // Nome da categoria
    } else {
        echo "<p>Categoria não encontrada.</p>";
        exit;
    }

    if ($num > 0) {
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $produtos = [];
        // echo "<p>Nenhum produto encontrado para essa categoria.</p>";
    }
} else {
    echo "<p>Categoria inválida.</p>";
    exit;
}
?>


<div class="containerProdutos">

    <h1 class="titulo-categoria">Produtos da Categoria: <?php echo htmlspecialchars($catNome); ?></h1>

    <div class="produtos-layout">

        <aside class="filtros-lateral">
            <!-- FILTROS -->
            <form method="GET" action="explorar-produtos.php" class="filters-form">

                <select name="catID">
                    <option value="">Todas as categorias</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['catID'] ?>" <?= (isset($_GET['catID']) && $_GET['catID'] == $cat['catID']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['catNome']) ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="lojID">
                    <option value="">Todas as lojas</option>
                    <?php foreach ($lojas as $loj): ?>
                        <option value="<?= $loj['lojID'] ?>" <?= (isset($_GET['lojID']) && $_GET['lojID'] == $loj['lojID']) ? 'selected' : '' ?>><?= htmlspecialchars($loj['lojNome']) ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="number" step="0.01" name="minPrice" placeholder="Preço mínimo" value="<?= isset($_GET['minPrice']) ? htmlspecialchars($_GET['minPrice']) : '' ?>">
                <input type="number" step="0.01" name="maxPrice" placeholder="Preço máximo" value="<?= isset($_GET['maxPrice']) ? htmlspecialchars($_GET['maxPrice']) : '' ?>">

                <select name="sort">
                    <option value="">Ordenar</option>
                    <option value="price_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') ? 'selected' : '' ?>>Preço: menor para maior</option>
                    <option value="price_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') ? 'selected' : '' ?>>Preço: maior para menor</option>
                    <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Novos</option>
                    <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : '' ?>>Antigos</option>
                </select>

                <button type="submit" class="btn btn-filtrar">Filtrar</button>
                <a href="explorar-produtos.php" class="btn btn-limpar" style="margin-left:6px;">Limpar</a>
            </form>
        </aside>

        <section class="produtos-area">

            <div class="products-grid">
                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>

                        <!-- ID -->
                        <div class="product-card" data-product-id="<?php echo $produto['proID']; ?>" data-category="<?php echo $produto['proCatID']; ?>">

                            <!-- IMAGEM -->
                            <div class="product-image">
                                <img src="<?php echo $produto['proFoto']; ?>"
                                    alt="<?php echo htmlspecialchars($produto['proNome']); ?>"
                                    loading="lazy">

                                <!-- BOTAO FAV -->
                                <button class="favorite-btn <?php echo isInFavorites($produto['proID']) ? 'active' : ''; ?>"
                                    data-product-id="<?php echo $produto['proID']; ?>">
                                    <i class="bi bi-heart-fill"></i>
                                </button>
                            </div>

                            <!-- INFORMAÇÕES -->
                            <div class="product-info">

                                <!-- NOME -->
                                <h3 class="product-name"><?php echo htmlspecialchars($produto['proNome']); ?></h3>

                                <!-- PREÇO -->
                                <div class="product-price"><?php echo formatPrice($produto['proPreco']); ?></div>
                                <!-- descrição -->
                                <p class="slogan">
                                    <?php echo nl2br(htmlspecialchars(substr($produto['proDescricao'], 0, 90)) . (strlen($produto['proDescricao']) > 100 ? '...' : '')); ?>
                                </p>
                                <!-- BOTAO -->
                                <div class="product-actions">
                                    <a href="produto.php?proID=<?php echo $produto['proID']; ?>" class="btn-product">
                                        Ver Detalhes
                                    </a>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <div class="aviso-categoria">
                        <p>Nenhum produto encontrado para essa categoria.</p>
                    </div>
                <?php endif; ?>

            </div>

        </section>

    </div>
    <?php require_once("utils/footer.php");
