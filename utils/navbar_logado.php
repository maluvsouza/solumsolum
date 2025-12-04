<!-- <div class="mobile-search-bar">
  <div class="mobile-search-container">
    <a href="index.php" class="mobile-logo">
      <img src="assets/logo/logo-solum.png" alt="Solum">
    </a>
    <form class="mobile-search-form" action="buscar-produtos.php" method="GET">
      <input type="text" name="q" placeholder="Buscar produtos sustentáveis" id="mobileSearchInput">
      <button type="submit" class="btnSearch" aria-label="Pesquisar">
        <i class="fas fa-search"></i>
      </button>
    </form>
  </div>
</div> -->


<div class="navlogada">

  <div class="navContainer">

    <!-- logoo -->
    <div class="navLogo">
      <a href="index.php">
        <img src="assets/logo/logo-solum.png" alt="Logo da Empresa" class="navlogoImagem">
      </a>
    </div>


    <!-- busca -->
    <div class="navBusca">
      <form class="formPesquisa" action="buscar-produtos.php" method="GET">
        <input type="text" name="q" placeholder="Buscar produtos sustentáveis" id="inputPesquisaLogada">
        <button type="submit" class="btnSearch" aria-label="Pesquisar">
          <i class="fas fa-search"></i>
        </button>
      </form>
    </div>

    <!-- icones -->
    <div class="navIcones">

      <!-- icone sobre nos -->
      <a href="sobre-nos.php" class="icone-Sobre" title="Sobre nós">
        <i class="bi bi-question-circle"></i>
      </a>

      <!-- icone carrinho -->
      <div class="navlinkCarrinho">
        <a href="carrinho.php" style="text-decoration:none;">
          <i class="fa fa-shopping-cart"></i>
          <span id="contadorCarrinho" class="badge-contador">
            0
          </span>
        </a>
      </div>

      <!-- icone favoritos -->
      <div class="navlinkFavoritos">

        <div class="icon-fav" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
          aria-controls="offcanvasScrolling">
          <span class="icone">
            <i class="bi bi-suit-heart azul" style="font-size: 1.7rem;"></i>
          </span>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="true"
          tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">

          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Favoritos</h5>
          </div>

          <div class="offcanvas-body">
            <div id="favoritosContainer">
              <p>Você ainda não adicionou produtos aos favoritos.</p>
            </div>
          </div>

        </div>

      </div>



      <!-- icone conta -->
      <div class="navlink perfil-dropdown">
        <div class="perfil-btn">
          <i class="bi bi-person-gear"></i>
          <?php if (isset($_SESSION['usuNome'])): ?>
            <span class="txt-welcome">Olá, <?php echo htmlspecialchars($_SESSION['usuNome']); ?>!</span>
          <?php endif; ?>
        </div>

        <!-- DROPDOWN -->
        <div class="perfil-menu">
          <a href="alterar-dados.php"><i class="bi bi-gear-fill"></i> Alterar dados</a>
          <a href="pag-vender.php"><i class="bi bi-house-fill"></i>Minha Loja</a>
          <a href="cadastrar-pagamento.php"><i class="bi bi-credit-card-2-front-fill"></i> Métodos de pagamento</a>
          <a href="encerrar-sessao.php"><i class="bi bi-box-arrow-left"></i> Sair da conta</a>
        </div>
      </div>

      <span class="linhaDivisor">│</span>
      <a href="vender.php" class="btnQueroVender">Quero vender</a>

    </div>
  </div>
</div>

<div class="navCategorias">

  <div class="navlinkprodutos">
    <a href="explorar-produtos.php"> Produtos </a>
  </div>

  <span class="branco">|</span>

  <div class="navlinkcategoria">
    <div class="dropbtn"> Categorias </div>
    <div class="dropdown-content">
      <?php require_once("handlerSelectCategorias.php") ?>

      <?php
      if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

          extract($row);

          echo "<a href='explorar-categorias.php?catID={$catID}'>{$catNome}</a> ";
        }
      } else {
        echo "<p>Nenhuma categoria foi encontrada</p>";
      }

      ?>

    </div>
  </div>

  <span class="branco">|</span>

  <div class="navlinkcategoria">
    <a href="explorarLojas.php"> Lojas </a>
  </div>

  <span class="branco">|</span>

  <div class="navlinkcategoria">
    <a href="minhas-solicitacoes.php"> Solicitações </a>
  </div>

</div>


<!-- MOBILE -->

<!-- <div class="mobile-bottom-nav">
  <a href="index.php" class="mobile-nav-item" title="Home">
    <i class="bi bi-house-fill"></i>
    <span>Home</span>
  </a>

  <button class="mobile-nav-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling" title="Favoritos">
    <i class="bi bi-suit-heart-fill"></i>
    <span>Favoritos</span>
  </button>

  <a href="explorar-produtos.php" class="mobile-nav-item" title="Categorias">
    <i class="bi bi-grid-3x3-gap-fill"></i>
    <span>Categorias</span>
  </a>

  <a href="carrinho.php" class="mobile-nav-item" title="Carrinho">
    <i class="bi bi-bag-fill"></i>
    <span id="mobile-cart-badge" class="mobile-cart-badge" style="display:none;">0</span>
  </a>

  <button class="mobile-nav-item" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" title="Perfil">
    <i class="bi bi-person-fill"></i>
    <span>Perfil</span>
  </button>
</div> -->