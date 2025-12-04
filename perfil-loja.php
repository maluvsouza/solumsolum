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

require_once("./utils/header.php");
if (isset($_SESSION['usuID'])) {

    require_once("./utils/navbar_logado.php");
} else {

    require_once("./utils/navbar_nao-logado.php");
}
?>

<?php require_once("handlerSelectLojas.php") ?>


<?php


$lojID = $_GET['lojID'] ?? null;

if ($lojID) {
    $loja = $lojaModel->readById($lojID);

    if ($loja) {
        $lojNome = $loja['lojNome'];
        $lojDescricao = $loja['lojDescricao'];
        $lojLogo = $loja['lojLogo'];
    } else {
        $lojNome = "Loja não encontrada";
        $lojDescricao = "";
        $lojLogo = "";
    }
} else {
    $lojNome = "ID de loja não informado";
    $lojDescricao = "";
    $lojLogo = "";
}
?>




<div class="container-perfil-loja">

    <!-- HEADER -->
    <div class="header-loja">

        <img src="<?= $loja['lojLogo'] ? 'assets/lojas/' . htmlspecialchars($loja['lojLogo']) : 'assets/imagens/placeholder-loja.png' ?>" alt="<?= htmlspecialchars($lojNome) ?>">
        <div class="info-loja">

            <h1><?= htmlspecialchars($lojNome) ?></h1>

            <p><?= htmlspecialchars($loja['lojDescricao']) ?></p>

            <div class="stats">
                <?php
                // load Produto model and get counts
                require_once 'model/Produto.php';
                $produtoModel = new Produto($db->getConnection());
                $totalProdutos = 0;
                $latestProdutos = [];
                $allProdutos = [];
                if ($lojID) {
                    $totalProdutos = $produtoModel->countByLoja($lojID);
                    $latestProdutos = $produtoModel->latestByLoja($lojID, 3);
                    $allProdutos = $produtoModel->allByLoja($lojID);
                }
                ?>
                <div>Produtos: <b><?= (int)$totalProdutos ?></b></div>
            </div>

        </div>
    </div>

    <!-- CUPOM -->
    <div class="cupom">
        <b>R$0,50 OFF</b> <span>Nas compras acima de R$50 </span>
        <button>Ativar</button>
    </div>

    <!-- PRODUTOS -->
    <h3 id="section-title">Destaques</h3>

    <div id="destaques" class="produtos tab-content" style="margin-top: 15px;">
        <?php if (!empty($latestProdutos)): ?>
            <?php foreach ($latestProdutos as $p): ?>
                <a href="produto.php?proID=<?= urlencode($p['proID']) ?>" class="produto-link" aria-label="Ver <?= htmlspecialchars($p['proNome']) ?>">
                    <div class="produto">
                        <img src="<?= htmlspecialchars($p['proFoto'] ?: 'https://via.placeholder.com/150') ?>" alt="<?= htmlspecialchars($p['proNome']) ?>">
                        <p>
                            <?= htmlspecialchars($p['proNome']) ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Não há produtos em destaque.</p>
        <?php endif; ?>
    </div>

    <div id="todos" class="produtos tab-content" style="display:none; flex-wrap:wrap; gap:12px;">
        <?php if (!empty($allProdutos)): ?>
            <?php foreach ($allProdutos as $p): ?>
                <a href="produto.php?proID=<?= urlencode($p['proID']) ?>" class="produto-link" aria-label="Ver <?= htmlspecialchars($p['proNome']) ?>">
                    <div class="produto">
                        <img src="<?= htmlspecialchars($p['proFoto'] ?: 'https://via.placeholder.com/150') ?>" alt="<?= htmlspecialchars($p['proNome']) ?>">
                        <p><?= htmlspecialchars($p['proNome']) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Esta loja ainda não possui produtos.</p>
        <?php endif; ?>
    </div>

    <script>
        // Simple tab switching
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.tab-link');
            const contents = document.querySelectorAll('.tab-content');
            const title = document.getElementById('section-title');

            function setActive(tab) {
                links.forEach(l => l.classList.toggle('active', l.dataset.tab === tab));
                contents.forEach(c => c.style.display = (c.id === tab) ? 'flex' : 'none');
                title.textContent = (tab === 'destaques') ? 'Destaques' : 'Todos os produtos';
            }

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    setActive(this.dataset.tab);
                });
            });

            // initial
            setActive('destaques');
        });
    </script>


</div>

<?php require_once("./utils/footer.php") ?>