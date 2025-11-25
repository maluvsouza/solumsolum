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
    <div class="container-display-fotos-produto"> <img class="produto-foto-destaque" src="<?php echo $produto['proFoto']; ?>" id="imagemPrincipal"> </img>
        <div class="produto-foto-frame">
            <img src="<?php echo $produto['proFoto2']; ?>" alt="Imagem 2" onclick="trocarImagem(this)">
            <img src="<?php echo $produto['proFoto3']; ?>" alt="Imagem 3" onclick="trocarImagem(this)">


        </div>
    </div>

    <div class="container-display-infos-produto">

        <spam class="produto-display-nome"><?= $proNome ?></spam><br>


        <p class="produto-display-preco">R$ <?= $proPreco ?>
            <!-- <php
 $lojNome = $produto->getLoja($proLojaID);
        echo "<p>Vendido por <a href='perfil-loja.php?lojID={$proLojaID}' style='text-decoration: none; color: #ff6b00;'> {$lojNome} </a></p>";
         ?> -->
            <br>
            <i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i><i class="bi bi-star"></i>
            <br>
            <?= $proDescricao ?>

            <br>

            <button style="background-color: whitesmoke; border: 0px ;" onclick="decrementar()">−</button>
            <span id="contador">0</span>
            <button style="background-color: whitesmoke; border: 0px ;" onclick="incrementar()">+</button>


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




            <br>

            <button class="btn btn-primary" id="addCarrinho">Adicionar ao carrinho <i class="bi bi-bag-plus-fill"></i></button>

            <!-- <button class="display-produto-botao-favorito"> Adiconar aos favoritos <i class="bi bi-bookmark-heart-fill"></i></button> -->

            <!-- <form class="display-produto-botao-carrinho" action="add-carrinho.php" method="post">
         <input type="submit" value="Adiconar ao carrinho">  <i class="bi bi-bag-plus-fill"></i>
        </form> -->
    </div>

</div>

<!-- <div class="container-display-produto-loja">Vendido por 
 
</div>  -->


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

    <h1 class="titulo">Explore outros produtos</h1>

    <p class="titulo-descricao">

    </p>

    <div class="products-grid">
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

                        <a href="produto.php?proID=<?php echo $produto['proID']; ?>" class="btn-primary">
                            Ver Detalhes
                        </a>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</div>















<?php require_once("./utils/footer.php") ?>