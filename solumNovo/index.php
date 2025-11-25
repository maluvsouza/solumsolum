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

<?php require_once("handlerSelectProduto.php") ?>
<?php require_once("handlerSelectProdutos.php") ?>


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

<section class="banner-destaque">

  <div class="banner-container">
    <div class="banner-texto">
      <h1>Conecte-se com um futuro sustentável </h1>
      <p>Descubra produtos ecológicos com propósito — um marketplace que valoriza o planeta e o consumo consciente.</p>
      <div class="banner-botoes">
        <a href="explorar-produtos.php" class="btn-principal">Explorar Produtos</a>
        <a href="#" class="btn-secundario">Quero Vender</a>
      </div>

      <div class="painel-destaques">
        <div class="destaque-item">
          <i class="fa-solid fa-leaf"></i>
          <span>+500 produtos eco</span>
        </div>
        <div class="destaque-item">
          <i class="fa-solid fa-earth-americas"></i>
          <span>Impacto positivo global</span>
        </div>
        <div class="destaque-item">
          <i class="fa-solid fa-recycle"></i>
          <span>Materiais reciclados</span>
        </div>
      </div>
    </div>

    <div class="banner-produtos">

      <?php foreach ($produtos as $produto): ?>

        <div class="produto-card ativa" data-product-id="<?php echo $produto['proID']; ?>" data-category="<?php echo $produto['proCatID']; ?>">

          <img src="<?php echo $produto['proFoto']; ?>" alt="<?php echo htmlspecialchars($produto['proNome']); ?>" class="produto-img">

          <h3><?php echo htmlspecialchars($produto['proNome']); ?></h3>

          <p class="slogan">
            <?php echo nl2br(htmlspecialchars(substr($produto['proDescricao'], 0, 80)) . (strlen($produto['proDescricao']) > 100 ? '...' : '')); ?>
          </p>

          <p class="promo-badge">50% OFF</p>
          <?php
          $precoOriginal = $produto['proPreco'];
          $precoDesconto = $precoOriginal * 0.5;
          ?>
          <span class="preco-antigo">R$ <?php echo formatPrice($precoOriginal); ?></span>
          <span class="preco-promo">R$ <?php echo formatPrice($precoDesconto); ?></span>

          <div class="card-info">
            <a href="produto.php?proID=<?php echo $produto['proID']; ?>" class="btn-visitar">
              Ver produto
            </a>
          </div>

        </div>

      <?php endforeach; ?>

    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const cards = document.querySelectorAll('.produto-card');
      let i = 0;

      // Inicializa: mostra apenas o primeiro card
      cards.forEach((card, index) => {
        card.classList.toggle('ativa', index === 0);
      });

      setInterval(() => {
        cards[i].classList.remove('ativa');
        i = (i + 1) % cards.length;
        cards[i].classList.add('ativa');
      }, 5000);
    });
  </script>
</section>


<div class="cardIndexContainer">
  <div class="cardIndex">
    <i class="bi bi-hand-thumbs-up cardIndexIcon"></i>
    <div class="cardIndexContent">
      <h3 class="cardIndexTitle">Nossa Missão</h3>
      <p class="cardIndexDescription">Promovemos um consumo mais consciente e acessível.
        Nosso objetivo é conectar pessoas a produtos sustentáveis que respeitam o meio ambiente e valorizam pequenos produtores.</p>
    </div>
  </div>

  <div class="cardIndex">
    <i class="bi bi-recycle cardIndexIcon"></i>
    <div class="cardIndexContent">
      <h3 class="cardIndexTitle">Produtos Sustentáveis e Éticos</h3>
      <p class="cardIndexDescription">Oferecemos uma seleção de itens ecológicos,
        reutilizáveis e veganos - de cuidados pessoais a utensílios para o dia a dia - todos com impacto reduzido no planeta. </p>
    </div>
  </div>

  <div class="cardIndex">
    <i class="bi bi-star cardIndexIcon"></i>
    <div class="cardIndexContent">
      <h3 class="cardIndexTitle">Juntos pelo Futuro</h3>
      <p class="cardIndexDescription">Ao escolher nossos produtos, você apoia práticas responsáveis,
        reduz seu impacto ambiental e contribui para um futuro mais limpo e justo para todos.</p>
    </div>
  </div>
</div>
<br>
<br>
<br>
<br>

<section class="secao-destaque">
  <div class="onda-verde">
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
      <path
        d="M0,40 C150,80 350,0 600,30 C850,60 1050,20 1200,40 L1200,120 L0,120 Z"
        fill="#3B3B3B"></path>
    </svg>
  </div>

  <div class="conteudo-destaque">
    <h2 class="titulo-destaque-lojas">Produtos em destaque</h2>
    <p class="subtitulo-destaque">
      Conheça os produtos que estão conquistando nossos clientes — qualidade, sustentabilidade e design em um só lugar.
    </p>

    <div class="carrossel-container">

      <button class="carrossel-btn prev"><i class="fa-solid fa-chevron-left"></i></button>

      <div class="carrossel">
        <?php foreach ($produtos as $produto): ?>
          <div class="card-produto"
            data-product-id="<?= $produto['proID']; ?>"
            data-category="<?= $produto['proCatID']; ?>">

            <img src="<?= $produto['proFoto']; ?>" alt="<?= htmlspecialchars($produto['proNome']); ?>" loading="lazy">

            <h3><?= htmlspecialchars($produto['proNome']); ?></h3>

            <div class="card-info">
              <p><?= nl2br(htmlspecialchars(substr($produto['proDescricao'], 0, 70)) . (strlen($produto['proDescricao']) > 100 ? '...' : '')); ?></p>
              <span class="preco-atual"><?= formatPrice($produto['proPreco']); ?></span>
              <a href="produto.php?proID=<?= $produto['proID']; ?>" class="btn-visitar">Ver produto</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <button class="carrossel-btn next"><i class="fa-solid fa-chevron-right"></i></button>

    </div>
  </div>
</section>

<?php require_once("./utils/footer.php") ?>

<!-- SCRIPT CARROSSEL -->
<script>
  document.addEventListener("DOMContentLoaded", () => {
    const carrossel = document.querySelector(".carrossel");
    const next = document.querySelector(".carrossel-btn.next");
    const prev = document.querySelector(".carrossel-btn.prev");

    const cardWidth = carrossel.querySelector(".card-produto").offsetWidth + 30; // largura + gap
    const visibleCards = Math.floor(carrossel.clientWidth / cardWidth);
    const scrollStep = cardWidth * visibleCards;

    next.addEventListener("click", () => {
      const maxScroll = carrossel.scrollWidth - carrossel.clientWidth;
      let newScroll = carrossel.scrollLeft + scrollStep;
      if (newScroll > maxScroll) newScroll = 0;
      carrossel.scrollTo({
        left: newScroll,
        behavior: "smooth"
      });
    });

    prev.addEventListener("click", () => {
      let newScroll = carrossel.scrollLeft - scrollStep;
      if (newScroll < 0) newScroll = carrossel.scrollWidth - carrossel.clientWidth;
      carrossel.scrollTo({
        left: newScroll,
        behavior: "smooth"
      });
    });

    setInterval(() => {
      next.click(); // simula clique no botão "next"
    }, 4000);
  });
</script>

<!-- SCRIPT MODAL SUCESSO/DUPLICADO/ERRO -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const cadastroStatus = urlParams.get("cadastro");

    if (cadastroStatus === "sucesso") {
      new bootstrap.Modal(document.getElementById('modalSucesso')).show();
    } else if (cadastroStatus === "duplicado") {
      new bootstrap.Modal(document.getElementById('modalDuplicado')).show();
    } else if (cadastroStatus === "erro") {
      new bootstrap.Modal(document.getElementById('modalErro')).show();
    }
  });
</script>