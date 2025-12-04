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

if (!isset($_SESSION['usuID'])) {
    echo "<script>
        alert('Você precisa estar logado para acessar o carrinho.');
        window.location.href = 'index.php';
    </script>";
    exit;
}


require_once("./utils/header.php");
if (isset($_SESSION['usuID'])) {

    require_once("./utils/navbar_logado.php");
} else {

    require_once("./utils/navbar_nao-logado.php");
}
?>



<div class="pagina-carrinho">

    <div class="carrinho-header">
        <h1><i class="fas fa-shopping-cart"></i> Meu Carrinho</h1>
        <p class="carrinho-subtitle">Revise seus produtos sustentáveis antes de finalizar</p>
    </div>

    <div class="carrinho-layout">

        <div class="carrinho-itens">
            <div class="carrinho-vazio" id="carrinhoVazio" style="display: none;">
                <i class="fas fa-shopping-cart"></i>
                <h3>Seu carrinho está vazio</h3>
                <p>Que tal adicionar alguns produtos sustentáveis?</p>

            </div>

            <div class="itens-lista" id="itensLista">

            </div>

            <div class="carrinho-acoes">
                <button class="btn-limpar-carrinho" onclick="limparCarrinho()">
                    <i class="fas fa-trash"></i>
                    Limpar Carrinho
                </button>
                <a href="index.php" class="btn-continuar-comprando">
                    <i class="fas fa-arrow-left"></i>
                    Continuar Comprando
                </a>
            </div>
        </div>


        <div class="resumo-pedido">
            <div class="resumo-card">
                <h3>Resumo do Pedido <i class="fas fa-leaf"></i></h3>

                <div class="resumo-linha">
                    <span>Subtotal</span>
                    <span id="subtotal">R$ 0,00</span>
                </div>

                <div class="resumo-linha">
                    <span>Frete</span>
                    <span id="valorFrete">Grátis</span>
                </div>

                <div class="resumo-linha desconto" id="linhaDesconto" style="display: none;">
                    <span>Desconto</span>
                    <span id="valorDesconto">-R$ 0,00</span>
                </div>

                <hr>

                <div class="resumo-total">
                    <span>Total</span>
                    <span id="valorTotal">R$ 0,00</span>
                </div>


                <div class="cupom-desconto">
                    <h4>Cupom de Desconto</h4>
                    <div class="cupom-input">
                        <input type="text" id="cupomInput" placeholder="Digite o cupom">
                        <button onclick="aplicarCupom()">Aplicar</button>
                    </div>
                    <div class="cupom-sugestoes">
                        <small>💚 ECO10 - 10% de desconto em pedidos acima de R$ 100</small>
                        <small>🌱 PRIMEIRA - 15% off na primeira compra</small>
                    </div>
                </div>


                <button class="btn-finalizar" onclick="finalizarCompra()" id="btnFinalizar" disabled>
                    <i class="fas fa-lock"></i>
                    Finalizar Compra
                </button>


                <div class="garantias">
                    <div class="garantia-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>Compra Segura</span>
                    </div>
                    <div class="garantia-item">
                        <i class="fas fa-truck"></i>
                        <span>Frete Grátis acima de R$ 99</span>
                    </div>
                    <div class="garantia-item">
                        <i class="fas fa-undo"></i>
                        <span>30 dias para troca</span>
                    </div>
                </div>
            </div>

        </div>
    </div>



    <script src="js/carrinho.js"></script>
    </body>

    </html>



    <?php require_once("./utils/footer.php") ?>