<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();
// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

require_once("./utils/header.php");
if (isset($_SESSION['usuID'])) {
    // Usuário logado
    require_once("./utils/navbar_logado.php");
} else {
    // Usuário não logado
    require_once("./utils/navbar_nao-logado.php");
}
?>


<?php require_once("handlerSelectProduto.php") ?>





<div class="container-display-produto">

    <!--FOTOS-->
    <div class="container-display-fotos-produto"> <img class="produto-foto-destaque" src="<?php echo $produto['proFoto']; ?>" id="imagemPrincipal"> </img>
        <div class="produto-foto-frame">
            <img src="<?php echo $produto['proFoto2']; ?>" alt="Imagem 2" onclick="trocarImagem(this)">
            <img src="<?php echo $produto['proFoto3']; ?>" alt="Imagem 3" onclick="trocarImagem(this)">
        </div>
    </div>

    <!-- NOME, PREÇO, ESTRELAS, DESCRIÇÃO, QUANTIDADE, BOTÃO -->
    <div class="container-display-infos-produto">

        <spam class="produto-display-nome"><?= $proNome ?></spam><br>

        <p class="produto-display-preco">R$ <?= $proPreco ?></p>

        <!-- <i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i> -->

        <div class="produto-descricao"><?= $proDescricao ?></div>

        <label class="quantidade">Quantidade:</label><br>

        <div class="quantidade-container">
            <button onclick="decrementar()">−</button>
            <span id="contador">0</span>
            <button onclick="incrementar()">+</button>
        </div>

        <!-- script do contador -->
        <script>
            let contador = 0;

            function atualizarDisplay() {
                document.getElementById('contador').textContent = contador;
            }

            function incrementar() {
                contador++;
                atualizarDisplay();
            }

            function decrementar() {
                if (contador > 0) {
                    contador--;
                    atualizarDisplay();
                }
            }
        </script>

        <button class="display-produto-botao-carrinho" id="addCarrinho">Adicionar ao carrinho <i class="bi bi-bag-plus-fill"></i></button>

    </div>

</div>

<script>
    document.getElementById('addCarrinho').addEventListener('click', function() {
        const nome = <?= json_encode($proNome) ?>;
        const preco = parseFloat(<?= json_encode($proPreco) ?>);
        const descricao = <?= json_encode($proDescricao) ?>;
        const imagem = document.getElementById('imagemPrincipal').src;
        const quantidade = parseInt(document.getElementById('contador').textContent);
        const loja = "Solum";

        if (quantidade < 1) {
            alert("Escolha ao menos 1 unidade do produto.");
            return;
        }

        const carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];


        const itemExistente = carrinho.find(item => item.nome === nome && item.preco === preco);

        if (itemExistente) {
            itemExistente.quantidade += quantidade;
        } else {
            carrinho.push({
                id: Date.now(),
                nome: nome,
                preco: preco,
                precoOriginal: null,
                imagem: imagem,
                loja: loja,
                quantidade: quantidade
            });
        }

        localStorage.setItem('carrinho', JSON.stringify(carrinho));

        alert('Produto adicionado ao carrinho!');
        // Atualiza badges (se a função estiver definida em carrinho.js)
        if (typeof window.refreshBadges === 'function') {
            window.refreshBadges();
        }
    });
</script>



<?php
function isInFavorites($productId)
{
    return isset($_SESSION['favorites']) && in_array($productId, $_SESSION['favorites']);
}


function formatPrice($price)
{
    return 'R$ ' . number_format($price, 2, ',', '.');
}
?>


<?php require_once("handlerSelectProdutos.php") ?>
<div class="containerProdutos">

    <h1 class="titulo-explore">Explore outros produtos</h1>

    <div class="produtos-layout">

        <section class="produtos-area">

            <div class="products-grid">
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
            </div>
        </section>

    </div>
</div>

<?php require_once("./utils/footer.php") ?>