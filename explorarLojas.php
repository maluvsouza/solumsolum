<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();

?>
<?php require_once("./utils/header.php");

if (isset($_SESSION['usuID'])) {
  require_once("./utils/navbar_logado.php");
} else {
  require_once("./utils/navbar_nao-logado.php");
}
?>

<?php
function generateStars($rating)
{
  $stars = '';
  $fullStars = floor($rating);
  $hasHalfStar = ($rating - $fullStars) >= 0.5;

  for ($i = 0; $i < $fullStars; $i++) {
    $stars .= '<i class="fas fa-star"></i>';
  }

  if ($hasHalfStar) {
    $stars .= '<i class="fas fa-star-half-alt"></i>';
    $fullStars++;
  }

  for ($i = $fullStars; $i < 5; $i++) {
    $stars .= '<i class="far fa-star"></i>';
  }

  return $stars;
}
?>
<?php require_once("handlerSelectLojas.php") ?>

<div class="containerLojas">x

  <div class="lojas-layout">


    <section class="lojas-area">
      <div class="stores-grid">
        <?php foreach ($lojas as $loja): ?>

          <!-- Início do card da loja -->
          <div class="store-card" data-store-id="<?= $loja['lojID'] ?>">

            <div class="store-header">
              <img src="<?= $loja['lojLogo'] ? 'assets/lojas/' . htmlspecialchars($loja['lojLogo']) : 'assets/imagens/placeholder-loja.png' ?>" alt="<?= htmlspecialchars($loja['lojNome']) ?>" loading="lazy">
            </div>

            <div class="store-info">

              <h3 class="store-name"><?= htmlspecialchars($loja['lojNome']) ?></h3>

              <!-- <div class="store-rating">
                <div class="stars">
                  <?= generateStars(0)  ?>
                </div>
                <span class="rating-text">0.0/5</span>
              </div> -->

              <!-- CONTAGEM DE PRODUTOS -->
              <?php
              // Contagem de produtos para a loja atual
              $query = "SELECT COUNT(*) AS product_count FROM produtos WHERE proLojaID = :lojID";
              $stmt = $conn->prepare($query);
              $stmt->bindParam(':lojID', $loja['lojID'], PDO::PARAM_INT);
              $stmt->execute();
              $product_count = $stmt->fetch(PDO::FETCH_ASSOC)['product_count'];
              ?>

              <div class="store-stats">
                <i class="fas fa-box"></i>
                <?= $product_count . ' produtos' ?>
              </div>

              <p class="store-description"><?= htmlspecialchars($loja['lojDescricao']) ?></p>

              <a href="perfil-loja.php?lojID=<?= $loja['lojID'] ?>" class="btn btn-secondary" target="_blank">
                <i class="fas fa-store"></i> Visitar Loja
              </a>

            </div>

          </div>
          <!-- Fim do card da loja -->

        <?php endforeach; ?>
      </div>
    </section>

  </div>
</div>

<?php require_once("./utils/footer.php") ?>