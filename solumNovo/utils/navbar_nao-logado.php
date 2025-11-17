<div class="mobile-search-bar">
  <div class="mobile-search-container">
    <a href="index.php" class="mobile-logo">
      <img src="assets/imagens/logo-solum.png" alt="Solum">
    </a>
    <form class="mobile-search-form" action="buscar-produtos.php" method="GET">
      <input type="text" name="q" placeholder="Buscar produtos sustentáveis" id="mobileSearchInput">
      <button type="submit" class="btnSearch" aria-label="Pesquisar">
        <i class="fas fa-search"></i>
      </button>
    </form>
  </div>
</div>

<div class="navNaoLogada">

  <div class="navContainer">

    <!-- logoo -->
    <div class="navLogo">
      <a href="index.php">
        <img src="assets/logo-solum.png" alt="Logo da Empresa" class="navlogoImagem">
      </a>
    </div>

    <!-- busca -->
    <div class="navBusca">
      <form class="formPesquisa" action="buscar_produtos.php" method="GET">
        <input type="text" name="q" placeholder="Buscar produtos sustentáveis" id="inputPesquisaLogada">
        <button type="submit" class="btnSearch" aria-label="Pesquisar">
          <i class="fas fa-search"></i>
        </button>
      </form>
    </div>


    <!-- icones -->
    <div class="navIcones">

      <a href="sobre-nos.php" class="icone-link" title="Sobre nós">
        <i class="bi bi-question-circle"></i>
      </a>

      <!-- USUÁRIO (abre modal ao clicar em "Entrar ou Cadastrar") -->
      <div class="usuario-link" style="cursor:pointer;">
        <i class="bi bi-person-fill"></i>
        <div class="usuario-texto">

          <span class="bemVindo">Seja bem-vindo!</span>

          <!-- ESTE é o gatilho do modal -->
          <span class="entreCadtr"
            data-bs-toggle="modal"
            data-bs-target="#modalLogin">
            <strong>Entrar ou Cadastrar</strong>
          </span>

        </div>
      </div>

      <span class="linhaDivisor">│</span>
      <a href="vender.php" class="btnQueroVender">Quero vender</a>

    </div>
  </div>
</div>


<!-- ############### MODAL LOGIN ############### -->
<div class="modal fade" id="modalLogin" tabindex="-1" aria-labelledby="modalLoginLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalLoginLabel">Entrar na Conta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form action="login.php" method="POST">
          <div class="mb-3">
            <label class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Senha</label>
            <input type="password" name="senha" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-arrow-in-right"></i> Entrar
          </button>
        </form>

        <hr>

        <div class="text-center">
          Ainda não tem conta?
          <a href="cadastro.php"><strong>Cadastre-se aqui</strong></a>
        </div>

      </div>

    </div>
  </div>
</div>


<div class="navCategorias">

  <div class="navlinkprodutos">
    <a href="explorar_produtos.php"> Produtos </a>
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
    <a href="lojas.php"> Lojas </a>
  </div>

</div>