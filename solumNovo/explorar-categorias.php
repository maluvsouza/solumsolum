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
    
    require_once( "./utils/navbar_logado.php");
} else {
   
    require_once( "./utils/navbar_nao-logado.php");
}

// helper functions (same as explorar-produtos.php)
function isInFavorites($productId) {
    return isset($_SESSION['favorites']) && in_array($productId, $_SESSION['favorites']);
}

function formatPrice($price) {
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
        echo "<p>Nenhum produto encontrado para essa categoria.</p>";
    }
} else {
    echo "<p>Categoria inválida.</p>";
    exit;
}
?>


<div class="containerProdutos">

    <h1 class="titulo">Produtos da Categoria: <?php echo htmlspecialchars($catNome); ?></h1>

    <p class="titulo-descricao"></p>

    <div class="products-grid">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="product-card" data-product-id="<?php echo $produto['proID']; ?>" data-category="<?php echo $produto['proCatID']; ?>">
                    <div class="product-image">
                        <img src="<?php echo $produto['proFoto']; ?>"
                            alt="<?php echo htmlspecialchars($produto['proNome']); ?>"
                            loading="lazy">

                        <button class="favorite-btn <?php echo isInFavorites($produto['proID']) ? 'active' : ''; ?>"
                            data-product-id="<?php echo $produto['proID']; ?>">
                            <i class="bi bi-bookmarks"></i>
                        </button>
                    </div>

                    <div class="product-info">
                        <h3 class="product-name"><?php echo htmlspecialchars($produto['proNome']); ?></h3>
                        <div class="product-price"><?php echo formatPrice($produto['proPreco']); ?></div>

                        <div class="product-actions">
                            <a href="produto.php?proID=<?php echo $produto['proID']; ?>" class="btn-primary">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado para essa categoria.</p>
        <?php endif; ?>
    </div>

</div>
<?php require_once("utils/footer.php");
