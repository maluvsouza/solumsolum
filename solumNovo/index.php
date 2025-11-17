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
    
    require_once( "./utils/navbar_logado.php");
} else {
   
    require_once( "./utils/navbar_nao-logado.php");
}

?>

<section class="banner-destaque">

  <div class="banner-container">
    <div class="banner-texto">
      <h1>Conecte-se com um futuro sustentável </h1>
      <p>Descubra produtos ecológicos com propósito — um marketplace que valoriza o planeta e o consumo consciente.</p>
      <div class="banner-botoes">
        <a href="#" class="btn-principal">Explorar Produtos</a>
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

      <div class="produto-card">
        <img src="imagens/foto1.jpg" alt="SuperCream" class="produto-img">
        <h3>Super Cream</h3>
        <p class="slogan">Super Cream chocolate com avelã</p>
        <p class="promo-badge">50% OFF </p>
        <span class="preco-antigo">R$ 199,90</span>
        <span class="preco-atual">R$ 99,90</span>
        <div class="avaliacao">⭐⭐⭐⭐ (4.3)</div>
        <a href="#" class="btn-visitar">Ver produto</a>
      </div>

      <div class="produto-card">
        <img src="imagens/foto2.jpg" alt="SuperCream" class="produto-img">
        <h3>Super Cream</h3>
        <p class="slogan">Super Cream chocolate com avelã</p>
        <p class="promo-badge">50% OFF </p>
        <span class="preco-antigo">R$ 199,90</span>
        <span class="preco-atual">R$ 99,90</span>
        <div class="avaliacao">⭐⭐⭐⭐ (4.3)</div>
        <a href="#" class="btn-visitar">Ver produto</a>
      </div>

      <div class="produto-card">
        <img src="imagens/foto3.jpg" alt="SuperCream" class="produto-img">
        <h3>Super Cream</h3>
        <p class="slogan">Super Cream chocolate com avelã</p>
        <p class="promo-badge">50% OFF </p>
        <span class="preco-antigo">R$ 199,90</span>
        <span class="preco-atual">R$ 99,90</span>
        <div class="avaliacao">⭐⭐⭐⭐ (4.3)</div>
        <a href="#" class="btn-visitar">Ver produto</a>
      </div>


    </div>
  </div>

  <script>
    const cards = document.querySelectorAll('.produto-card');
    let i = 0;
    setInterval(() => {
      cards[i].classList.remove('ativa');
      i = (i + 1) % cards.length;
      cards[i].classList.add('ativa');
    }, 5000);
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
        <div class="card-produto">
          <img src="imagens/foto1.jpg" alt="Shampoo Natural">
          <h3>Shampoo Natural EcoVida</h3>
          <p>Feito com óleos essenciais e embalagem biodegradável.</p>
          <div class="avaliacao">⭐⭐⭐⭐⭐ (4.9)</div>
          <a href="#" class="btn-visitar">Ver produto</a>
        </div>

        <div class="card-produto">
          <img src="imagens/foto2.jpg" alt="Garrafa Eco">
          <h3>Garrafa VerdePuro</h3>
          <p>Reutilizável, elegante e feita em aço inox com bambu natural.</p>
          <div class="avaliacao">⭐⭐⭐⭐ (4.8)</div>
          <a href="#" class="btn-visitar">Ver produto</a>
        </div>

        <div class="card-produto">
          <img src="imagens/foto3.jpg" alt="Sabonete Naturalize">
          <h3>Sabonete Naturalize</h3>
          <p>Produzido com ingredientes orgânicos e fragrâncias suaves.</p>
          <div class="avaliacao">⭐⭐⭐⭐⭐ (5.0)</div>
          <a href="#" class="btn-visitar">Ver produto</a>
        </div>

        <div class="card-produto">
          <img src="imagens/foto1.jpg" alt="Kit Sustentável">
          <h3>Kit Sustentável Essencial</h3>
          <p>Escova de bambu, copo dobrável e canudo reutilizável.</p>
          <div class="avaliacao">⭐⭐⭐⭐⭐ (5.0)</div>
          <a href="#" class="btn-visitar">Ver produto</a>
        </div>

        <div class="card-produto">
          <img src="imagens/foto2.jpg" alt="EcoBag">
          <h3>EcoBag de Algodão Orgânico</h3>
          <p>Durável, leve e perfeita para o dia a dia.</p>
          <div class="avaliacao">⭐⭐⭐⭐ (4.7)</div>
          <a href="#" class="btn-visitar">Ver produto</a>
        </div>

        <div class="card-produto">
          <img src="imagens/foto3.jpg" alt="Velas Naturais">
          <h3>Velas Naturais Puro Aroma</h3>
          <p>À base de cera vegetal e fragrâncias suaves.</p>
          <div class="avaliacao">⭐⭐⭐⭐⭐ (4.9)</div>
          <a href="#" class="btn-visitar">Ver produto</a>
        </div>

        <div class="card-produto">
          <img src="imagens/foto1.jpg" alt="Difusor Verde">
          <h3>Difusor Verde Aroma</h3>
          <p>Traga frescor e natureza ao seu ambiente.</p>
          <div class="avaliacao">⭐⭐⭐⭐ (4.8)</div>
          <a href="#" class="btn-visitar">Ver produto</a>
        </div>
      </div>

      <button class="carrossel-btn next"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
  </div>
</section>

<?php require_once("./utils/footer.php") ?>


<script>
  const carrossel = document.querySelector(".carrossel");
  const next = document.querySelector(".carrossel-btn.next");
  const prev = document.querySelector(".carrossel-btn.prev");

  let scrollAmount = 0;
  const cardWidth = carrossel.querySelector(".card-produto").offsetWidth + 30; // largura + gap
  const visibleCards = 4;
  const scrollStep = cardWidth * visibleCards;

  next.addEventListener("click", () => {
    scrollAmount += scrollStep;
    if (scrollAmount >= carrossel.scrollWidth - carrossel.clientWidth) {
      scrollAmount = 0;
    }
    carrossel.scrollTo({
      left: scrollAmount,
      behavior: "smooth"
    });
  });

  prev.addEventListener("click", () => {
    scrollAmount -= scrollStep;
    if (scrollAmount < 0) {
      scrollAmount = carrossel.scrollWidth - carrossel.clientWidth;
    }
    carrossel.scrollTo({
      left: scrollAmount,
      behavior: "smooth"
    });
  });
</script>