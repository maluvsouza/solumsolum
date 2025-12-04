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
echo '<link rel="stylesheet" href="css/cadastro.css">';

if (isset($_SESSION['usuID'])) {
    require_once("./utils/navbar_logado.php");
} else {
    require_once("./utils/navbar_nao-logado.php");
}

// Verifica se usuário está logado
if (!isset($_SESSION['usuID'])) {
    echo "<script>
        alert('Você precisa estar logado para criar uma loja.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

// Verifica se usuário é vendedor
require_once "config/db.php";
$database = new Database();
$conn = $database->getConnection();

$checkQuery = "SELECT vendID FROM vendedores WHERE vendUsuID = :usuID";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bindParam(':usuID', $_SESSION['usuID'], PDO::PARAM_INT);
$checkStmt->execute();

if ($checkStmt->rowCount() === 0) {
    echo "<script>
        alert('Você não é um vendedor registrado. Por favor, complete o cadastro de vendedor primeiro.');
        window.location.href = 'vender.php';
    </script>";
    exit;
}

// Obtém o vendID do usuário
$vendedor = $checkStmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['vendID'] = $vendedor['vendID'];

?>

<h1 class="titulo">Criar Sua Loja</h1>
<p class="descricao">Insira os dados da sua loja...</p>

<div class="cadLoja-container">
    <form class="loj-form" method="POST" action="handlerInsertLoja.php" enctype="multipart/form-data">

        <div class="cadLoja-campo">
            <label class="cadLoja-label">Nome da Loja</label>
            <input type="text" class="cadLoja-input" id="nomeLojaInput" name="loj-nome" placeholder="Ex: Solum Eco" required>
        </div>

        <div class="cadLoja-campo">
            <label class="cadLoja-label">Descrição da Loja</label>
            <textarea class="cadLoja-textarea" id="descricaoLojaInput" name="loj-descricao" placeholder="Descreva sua loja, produtos e valores..." required></textarea>
        </div>

        <div class="cadLoja-campo">
            <label class="cadLoja-label">Foto da Loja</label>
            <input type="file" class="cadLoja-arquivo" id="fotoLojaInput" name="loj-foto" accept="image/*" required>
            <small class="cadLoja-info">Formatos aceitos: JPG, PNG...</small>
        </div>

        <button type="submit" class="btn-cadLoja">Criar Loja</button>
        <a href="minhas-solicitacoes.php" class="btn-solicitacoes">📋 Ver Minhas Solicitações</a>

    </form>
</div>


<?php require_once("./utils/footer.php"); ?>