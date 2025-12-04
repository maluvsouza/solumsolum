<?php

ob_start();

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
echo '<link rel="stylesheet" href="css/pag-vender.css">';
if (isset($_SESSION['usuID'])) {

  require_once("./utils/navbar_logado.php");
} else {

  require_once("./utils/navbar_nao-logado.php");
}

// Verifica se usuário está logado
if (!isset($_SESSION['usuID'])) {
  echo "<script>
        alert('Você precisa estar logado para acessar essa página.');
        window.location.href = 'index.php';
    </script>";
  exit;
}

require_once "config/db.php";
$database = new Database();
$conn = $database->getConnection();

// Obtém dados do vendedor
$vendQuery = "SELECT vendID FROM vendedores WHERE vendUsuID = :usuID";
$vendStmt = $conn->prepare($vendQuery);
$vendStmt->bindParam(':usuID', $_SESSION['usuID'], PDO::PARAM_INT);
$vendStmt->execute();

if ($vendStmt->rowCount() === 0) {
  echo "<script>
        alert('Você não é um vendedor registrado.');
        window.location.href = 'vender.php';
    </script>";
  exit;
}

$vendedor = $vendStmt->fetch(PDO::FETCH_ASSOC);
$vendID = $vendedor['vendID'];

// Obtém dados da loja
$lojaQuery = "SELECT * FROM lojas WHERE lojVendedorID = :vendID";
$lojaStmt = $conn->prepare($lojaQuery);
$lojaStmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);
$lojaStmt->execute();

// Se não tem loja, verifica se tem loja pendente
if ($lojaStmt->rowCount() === 0) {
  // Verifica solicitações pendentes de loja
  $checkPendingStores = "SELECT id, data_json FROM approval_requests 
                          WHERE type = 'store' AND vendID = :vendID AND status = 'pending'";
  $checkStmt = $conn->prepare($checkPendingStores);
  $checkStmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);
  $checkStmt->execute();

  if ($checkStmt->rowCount() > 0) {
    // Tem loja pendente de aprovação
    ob_end_clean();
    header('Location: minhas-solicitacoes.php');
    exit;
  }

  // Não tem loja nem solicitação pendente
  ob_end_clean();
  header('Location: pag-cadastro-loja.php');
  exit;
}

$loja = $lojaStmt->fetch(PDO::FETCH_ASSOC);
$lojID = $loja['lojID'];

// Carrega produtos da loja
require_once __DIR__ . '/model/Produto.php';
$produtoModel = new Produto($conn);
$produtos = $produtoModel->allByLoja($lojID);
$produtosCount = is_array($produtos) ? count($produtos) : 0;

// Obtém dados do usuário
$usuQuery = "SELECT usuNome FROM usuarios WHERE usuID = :usuID";
$usuStmt = $conn->prepare($usuQuery);
$usuStmt->bindParam(':usuID', $_SESSION['usuID'], PDO::PARAM_INT);
$usuStmt->execute();
$usuario = $usuStmt->fetch(PDO::FETCH_ASSOC);

?>
<main>

  <aside class="menu-lateral">
    <div class="perfil">
      <div class="foto">
        <?php
        $fotoLoja = $loja['lojLogo'] ? 'assets/lojas/' . htmlspecialchars($loja['lojLogo']) : 'assets/imagens/placeholder-loja.png';
        ?>
        <img src="<?php echo $fotoLoja; ?>" alt="<?php echo htmlspecialchars($loja['lojNome']); ?>">
      </div>
      <h2><?php echo htmlspecialchars($loja['lojNome']); ?></h2>
      <button class="botao-perfil">Minha Loja</button>
    </div>

    <div class="menu">
      <ul>
        <li><a href="#" class="menu-item active" data-section="a-venda">À venda</a></li>
        <li><a href="#" class="menu-item" data-section="historico">Histórico de Vendas</a></li>
        <li><a href="#" class="menu-item" data-section="configuracoes">Configurações</a></li>
        <li><a href="minhas-solicitacoes.php" class="menu-item">📋 Minhas Solicitações</a></li>
      </ul>
    </div>
  </aside>

  <section class="conteudo">

    <div class="secao-conteudo active" id="a-venda">
      <div class="caixa">
        <h2>À venda</h2>
        <div class="area-rascunhos">
          <?php if (!empty($produtos) && $produtosCount > 0) { ?>
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
              <?php foreach ($produtos as $p) {
                $img = $p['proFoto'] ? htmlspecialchars($p['proFoto']) : 'assets/imagens/placeholder-produto.png';
              ?>
                <div style="width:220px; border:1px solid #eee; padding:8px; border-radius:6px; text-align:left;">
                  <div style="width:100%; height:140px; overflow:hidden; display:flex; align-items:center; justify-content:center;">
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($p['proNome']); ?>" style="max-width:100%; max-height:140px; object-fit:cover;">
                  </div>
                  <h4 style="margin:8px 0 4px; font-size:14px"><?php echo htmlspecialchars($p['proNome']); ?></h4>
                  <div style="font-size:13px; color:#666; margin-bottom:6px">R$ <?php echo number_format($p['proPreco'], 2, ',', '.'); ?></div>
                  <div style="display:flex; gap:6px;">
                    <a href="produto.php?id=<?php echo intval($p['proID']); ?>" class="botao-roxo" style="padding:6px 8px; font-size:13px;">Ver</a>
                  </div>
                </div>
              <?php } ?>
            </div>
            <div style="margin-top:12px; text-align:right;">
              <button id="abrirNovoAnuncio" class="botao-roxo">Criar novo anúncio</button>
            </div>
          <?php } else { ?>
            <p>Você ainda não tem produtos à venda.</p>
            <button id="abrirNovoAnuncio" class="botao-roxo">Criar novo anúncio</button>
          <?php } ?>
        </div>

        <!-- Modal / Formulário de novo anúncio -->
        <div id="modalNovoAnuncio" class="modal-overlay">
          <div class="modal-content">

            <button id="fecharNovoAnuncio" class="modal-close">&times;</button>

            <h3>Criar novo anúncio</h3>

            <?php
            require_once __DIR__ . '/config/db.php';
            require_once __DIR__ . '/model/Categoria.php';
            $catDb = new Database();
            $catConn = $catDb->getConnection();
            $categoriaModel = new Categoria($catConn);
            $catsStmt = $categoriaModel->readAll();
            $cats = $catsStmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <form method="POST" action="handlerInsertProduto.php" enctype="multipart/form-data">
              <input type="hidden" name="pro-loja-id" value="<?php echo intval($lojID); ?>">

              <div class="modal-grid">

                <div class="modal-col">
                  <label>Nome do Produto</label>
                  <input type="text" name="pro-nome" class="form-control" required>

                  <label>Descrição</label>
                  <textarea name="pro-descricao" class="form-control" rows="4" required></textarea>

                  <label>Categoria</label>
                  <select name="pro-cat" class="form-control" required>
                    <?php if (!empty($cats)) {
                      foreach ($cats as $row) {
                        echo '<option value="' . intval($row['catID']) . '">' . htmlspecialchars($row['catNome']) . '</option>';
                      }
                    } else {
                      echo '<option value="0">Sem categorias</option>';
                    } ?>
                  </select>
                </div>

                <div class="modal-col">
                  <label>Preço (R$)</label>
                  <input type="number" step="0.01" min="0" name="pro-preco" class="form-control" required>

                  <label>Quantidade em estoque</label>
                  <input type="number" min="0" name="pro-quant" class="form-control" required>

                  <label>Foto principal</label>
                  <input type="file" name="pro-foto" accept="image/*" class="form-control" required>

                  <label>Foto secundária</label>
                  <input type="file" name="pro-foto2" accept="image/*" class="form-control">

                  <label>Foto 3</label>
                  <input type="file" name="pro-foto3" accept="image/*" class="form-control">
                </div>

              </div>

              <div class="modal-actions">
                <button type="button" id="cancelarNovoAnuncio" class="btn-cancelar">Cancelar</button>
                <button type="submit" class="btn-publicar">Publicar Produto</button>
              </div>

            </form>

          </div>
        </div>

      </div>
    </div>


    <div class="secao-conteudo" id="historico">
      <div class="caixa">
        <h2>Histórico de Vendas</h2>
        <div class="area-historico">
          <p>Nenhuma venda registrada ainda</p>
        </div>
      </div>
    </div>


    <div class="secao-conteudo" id="configuracoes">
      <div class="caixa">
        <h2>Configurações da Loja</h2>
        <div class="area-configuracoes">
          <form class="form-configuracoes" method="POST" action="handlerUpdateLoja.php" enctype="multipart/form-data">
            <div class="form-group">
              <label for="nome-loja">Nome da Loja</label>
              <input type="text" id="nome-loja" name="loj-nome" value="<?php echo htmlspecialchars($loja['lojNome']); ?>" required>
            </div>
            <div class="form-group">
              <label for="descricao-loja">Descrição</label>
              <textarea id="descricao-loja" name="loj-descricao" required><?php echo htmlspecialchars($loja['lojDescricao']); ?></textarea>
            </div>
            <div class="form-group">
              <label for="foto-loja">Foto da Loja</label>
              <?php
              $fotoLojaAtual = $loja['lojLogo'] ? 'assets/lojas/' . htmlspecialchars($loja['lojLogo']) : null;
              if ($fotoLojaAtual) {
                echo '<div style="margin-bottom: 10px;">';
                echo '<img src="' . $fotoLojaAtual . '" alt="Foto atual" style="max-width: 200px; border-radius: 8px;">';
                echo '</div>';
              }
              ?>
              <input type="file" id="foto-loja" name="loj-foto" accept="image/*">
              <small class="form-text text-muted">Deixe em branco para manter a foto atual. Formatos aceitos: JPG, PNG, GIF (máximo 5MB)</small>
            </div>
            <button type="submit" class="botao-roxo">Salvar alterações</button>
          </form>
        </div>
      </div>
    </div>

  </section>
</main>

<script>
  // Script para alternar entre abas
  document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
      e.preventDefault();
      const section = this.getAttribute('data-section');

      // Remove active de todos
      document.querySelectorAll('.menu-item').forEach(m => m.classList.remove('active'));
      document.querySelectorAll('.secao-conteudo').forEach(s => s.classList.remove('active'));

      // Adiciona active ao clicado
      this.classList.add('active');
      document.getElementById(section).classList.add('active');
    });
  });
</script>
<script>
  // Modal novo anúncio
  const abrirBtn = document.getElementById('abrirNovoAnuncio');
  const modal = document.getElementById('modalNovoAnuncio');
  const fecharBtn = document.getElementById('fecharNovoAnuncio');
  const cancelarBtn = document.getElementById('cancelarNovoAnuncio');

  if (abrirBtn && modal) {
    abrirBtn.addEventListener('click', function(e) {
      e.preventDefault();
      modal.style.display = 'block';
      window.scrollTo(0, 0);
    });
  }
  if (fecharBtn) fecharBtn.addEventListener('click', () => modal.style.display = 'none');
  if (cancelarBtn) cancelarBtn.addEventListener('click', () => modal.style.display = 'none');
  // Fecha ao clicar fora do conteúdo
  window.addEventListener('click', function(e) {
    if (e.target === modal) modal.style.display = 'none';
  });
</script>

<?php require_once("./utils/footer.php"); ?>