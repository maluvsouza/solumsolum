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


<div class="containerLojas">

  <h1 class="titulo">Nossas Lojas Parceiras</h1>

  <p class="titulo-descricao">
    Conheça as lojas que compartilham nossa missão de sustentabilidade
  </p>


  <form method="GET" action="lojas.php" class="filters-form" style="margin-bottom:16px; display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
    <input type="text" name="q" placeholder="Buscar por nome" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" style="padding:8px; border:1px solid #ddd; border-radius:0;">
    <input type="number" name="minProducts" placeholder="Mínimo de produtos" value="<?= isset($_GET['minProducts']) ? (int)$_GET['minProducts'] : '' ?>" style="width:160px; padding:8px; border:1px solid #ddd; border-radius:0;">
    <select name="sort" style="padding:8px; border:1px solid #ddd; border-radius:0;">
      <option value="">Ordenar</option>
      <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort']=='name_asc') ? 'selected' : '' ?>>Nome A-Z</option>
      <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort']=='name_desc') ? 'selected' : '' ?>>Nome Z-A</option>
      <option value="products_desc" <?= (isset($_GET['sort']) && $_GET['sort']=='products_desc') ? 'selected' : '' ?>>Mais produtos</option>
      <option value="products_asc" <?= (isset($_GET['sort']) && $_GET['sort']=='products_asc') ? 'selected' : '' ?>>Menos produtos</option>
    </select>
    <button type="submit" class="btn btn-primary">Filtrar</button>
    <a href="lojas.php" class="btn btn-secondary" style="margin-left:6px;">Limpar</a>
  </form>

  <div class="stores-grid">



    <?php foreach ($lojas as $loja): ?>
  <div class="store-card" data-store-id="<?= $loja['lojID'] ?>">

    <div class="store-header">
      <img src="<?= $loja['lojLogo'] ? 'assets/lojas/' . htmlspecialchars($loja['lojLogo']) : 'assets/imagens/placeholder-loja.png' ?>" alt="<?= htmlspecialchars($loja['lojNome']) ?>" loading="lazy">
    </div>

    <div class="store-info">

      <h3 class="store-name"><?= htmlspecialchars($loja['lojNome']) ?></h3>

      
      <div class="store-rating">
        <div class="stars">
          <?= generateStars(0)  ?>
        </div>
        <span class="rating-text">0.0/5</span>
      </div>

      <div class="store-stats">
        <i class="fas fa-box"></i>
        <?= isset($loja['product_count']) ? (int)$loja['product_count'] . ' produtos' : '0 produtos' ?>
      </div>

      <p class="store-description"><?= htmlspecialchars($loja['lojDescricao']) ?></p>

      <a href="perfil-loja.php?lojID=<?php echo $loja['lojID']; ?>" class="btn btn-secondary" target="_blank">
        <i class="fas fa-store"></i> Visitar Loja
      </a>

    

    </div>

  </div>
<?php endforeach; ?>


  </div>

</div>




<?php require_once("./utils/footer.php") ?>