<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();

require_once("./utils/header.php");

if (isset($_SESSION['usuID'])) {
    require_once("./utils/navbar_logado.php");
} else {
    require_once("./utils/navbar_nao-logado.php");
}

require_once("handlerSelectProdutos.php"); 
?>

<br>

<?php
echo "<div class='explorar-container'>";
echo "<div class='lista-produtos'>";

if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $stmt = $produto->buscarProdutos($_GET['q']);
} else {
    $stmt = $produto->readAll();
}

$num = $stmt->rowCount();

if ($num > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<div class='product-card'>";
        echo "<img class='product-img' src='" . $row['proFoto'] . "' alt='" . $row['proNome'] . "' width='150'>";
        echo "<h3 class='product-name'>" . $row['proNome'] . "</h3>";
        echo "<p class='slogan'>" . $row['proDescricao'] . "</p>";
        echo "<p class='product-price'>R$ " . number_format($row['proPreco'], 2, ',', '.') . "</p>";
        $lojNome = $produto->getLoja($row['proLojaID']);
        echo "<p>Vendido por <a href='loja.php?lojID=" . $row['proLojaID'] . "'>" . $lojNome . "</a></p>";
        echo "<a class='btn-product' href='produto.php?proID=" . $row['proID'] . "'>Ver produto</a>";
        echo "</div>";
    }
} else {
    echo "<p>Nenhum produto foi encontrado.</p>";
}

          

echo "</div>"; 
echo "</div>"; 
?>

<?php require_once("./utils/footer.php"); ?>