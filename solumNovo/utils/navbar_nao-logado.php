<div class="mobile-search-bar">
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
</div>

<div class="navNaoLogada">

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
          <a href="#"
            data-bs-toggle="modal"
            data-bs-target="#modalCadastro">
            <strong>Cadastre-se aqui</strong>
          </a>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- ################ MODAL CADASTRO ################ -->
<div class="modal fade" id="modalCadastro" tabindex="-1" aria-labelledby="modalCadastroLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius:18px;">

      <div class="modal-header">
        <h5 class="modal-title" id="modalCadastroLabel">Criar Conta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <form class="row g-3" method="POST" action="handlerInsertUsuario.php">

          <div class="col-md-6">
            <label class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">E-mail</label>
            <input type="email" class="form-control" name="email" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Senha</label>
            <input type="password" class="form-control" name="senha" required>
            <small class="text-muted">Sua senha deve ter entre 8 e 20 caracteres.</small>
          </div>

          <div class="col-md-6">
            <label class="form-label">Cidade</label>
            <select class="form-select" name="cidade" required>
              <option selected>Escolha...</option>
              <?php
              require("handlerSelectCidade.php");
              if ($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  echo "<option>{$row['cidNome']}</option>";
                }
              }
              ?>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">CEP</label>
            <input type="text" class="form-control" name="cep" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Telefone</label>
            <input type="text" class="form-control" name="telefone" required>
          </div>

          <div class="col-md-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="gridCheck" required>
              <label class="form-check-label" for="gridCheck">
                Ao utilizar este site, você concorda com a Política de Privacidade.
              </label>
            </div>
            <label class="text-muted" style="font-size:0.85rem;">
              Não utilize seus dados reais.
            </label>
          </div>

          <div class="col-12">
            <button type="submit" class="btn btn-primary w-100" style="background:#f9b487; border:none;">
              Criar conta
            </button>
          </div>

          <div class="text-center mt-2">
            Já possui conta?
            <span data-bs-toggle="modal"
              data-bs-target="#modalLogin"
              style="cursor:pointer; color:#174143; font-weight:700;">
              Entrar aqui
            </span>
          </div>

        </form>

      </div>
    </div>
  </div>
</div>

<!-- ################ MODAL SUCESSO ################ -->
<div class="modal fade" id="modalSucesso" tabindex="-1" aria-labelledby="modalSucessoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header text-center">
        <h5 class="modal-title" id="modalSucessoLabel" style="color: var(--texto);">Cadastro concluído</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <i class="bi bi-check-circle-fill" style="color: var(--destaque);"></i>
        <p class="mt-3">
          Seu cadastro foi realizado com sucesso!
        </p>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DUPLICADO -->
<div class="modal fade" id="modalDuplicado" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title">E-mail já cadastrado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-exclamation-triangle-fill" style="color: var(--yellow);"></i>
        <p class="mt-3">Este e-mail já está em uso. Tente outro.</p>
      </div>
    </div>
  </div>
</div>

<!-- MODAL ERRO -->
<div class="modal fade" id="modalErro" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h5 class="modal-title">Erro no cadastro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <i class="bi bi-x-circle-fill" style="color: var(--error-color);"></i>
        <p class="mt-3">Ocorreu um erro inesperado. Tente novamente mais tarde.</p>
      </div>
    </div>
  </div>
</div>

<!-- nav - produtos, categorias, etc -->
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
    <a href="lojas.php"> Lojas </a>
  </div>

</div>




<script>
  document.addEventListener("DOMContentLoaded", function() {
    const abrirCadastro = document.querySelector(".abrirModalCadastro");

    abrirCadastro.addEventListener("click", function() {
      let modalLogin = bootstrap.Modal.getInstance(document.getElementById('modalLogin'));
      modalLogin.hide();
    });
  });
</script>