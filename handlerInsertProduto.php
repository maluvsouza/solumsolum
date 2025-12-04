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

require_once "config/db.php";
require_once "utils/upload.php";

if (!isset($_SESSION['usuID'])) {
    echo "<script>alert('Você precisa estar logado para cadastrar produtos.');window.location.href='index.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pag-vender.php');
    exit;
}

// Campos do formulário
$proNome = trim($_POST['pro-nome'] ?? '');
$proDescricao = trim($_POST['pro-descricao'] ?? '');
$proPreco = $_POST['pro-preco'] ?? '';
$proQuant = $_POST['pro-quant'] ?? '';
$proCatID = $_POST['pro-cat'] ?? null;
$proLojaID = $_POST['pro-loja-id'] ?? null;

// Validação básica
if ($proNome === '' || $proDescricao === '' || $proPreco === '' || $proQuant === '' || !$proLojaID) {
    echo "<script>alert('Por favor, preencha todos os campos obrigatórios.');window.history.back();</script>";
    exit;
}

$database = new Database();
$conn = $database->getConnection();

try {
    // Verifica se o usuário atual é o dono da loja
    $checkVendQuery = "SELECT v.vendID FROM vendedores v WHERE v.vendUsuID = :usuID LIMIT 1";
    $stmtCheck = $conn->prepare($checkVendQuery);
    $stmtCheck->bindParam(':usuID', $_SESSION['usuID'], PDO::PARAM_INT);
    $stmtCheck->execute();
    if ($stmtCheck->rowCount() === 0) {
        echo "<script>alert('Você não é um vendedor registrado.');window.location.href='vender.php';</script>";
        exit;
    }
    $vend = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    $vendID = $vend['vendID'];

    // Verifica se a loja pertence a esse vendedor
    $checkLojaQuery = "SELECT lojID FROM lojas WHERE lojID = :lojID AND lojVendedorID = :vendID LIMIT 1";
    $stmtLoja = $conn->prepare($checkLojaQuery);
    $stmtLoja->bindParam(':lojID', $proLojaID, PDO::PARAM_INT);
    $stmtLoja->bindParam(':vendID', $vendID, PDO::PARAM_INT);
    $stmtLoja->execute();
    if ($stmtLoja->rowCount() === 0) {
        echo "<script>alert('Loja não encontrada ou não pertence a você.');window.location.href='pag-vender.php';</script>";
        exit;
    }

    // Upload de até 3 imagens
    $uploadDir = __DIR__ . '/assets/produtos';
    $foto1 = null; $foto2 = null; $foto3 = null;

    if (isset($_FILES['pro-foto']) && $_FILES['pro-foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $r = uploadArquivo($_FILES['pro-foto'], $uploadDir);
        if (!$r['sucesso']) { throw new Exception('Erro upload foto 1: ' . $r['erro']); }
        // Armazena caminho web relativo para uso nas views
        $foto1 = 'assets/produtos/' . $r['arquivo'];
    }
    if (isset($_FILES['pro-foto2']) && $_FILES['pro-foto2']['error'] !== UPLOAD_ERR_NO_FILE) {
        $r = uploadArquivo($_FILES['pro-foto2'], $uploadDir);
        if (!$r['sucesso']) { throw new Exception('Erro upload foto 2: ' . $r['erro']); }
        $foto2 = 'assets/produtos/' . $r['arquivo'];
    }
    if (isset($_FILES['pro-foto3']) && $_FILES['pro-foto3']['error'] !== UPLOAD_ERR_NO_FILE) {
        $r = uploadArquivo($_FILES['pro-foto3'], $uploadDir);
        if (!$r['sucesso']) { throw new Exception('Erro upload foto 3: ' . $r['erro']); }
        $foto3 = 'assets/produtos/' . $r['arquivo'];
    }

    // Fetch category name for display
    $catName = '';
    try {
        $stmtCat = $conn->prepare('SELECT catNome FROM categorias WHERE catID = :catID');
        $stmtCat->bindParam(':catID', $proCatID, PDO::PARAM_INT);
        $stmtCat->execute();
        $cat = $stmtCat->fetch(PDO::FETCH_ASSOC);
        $catName = $cat['catNome'] ?? '';
    } catch (Exception $e) {
        // ignore
    }

    // Cria solicitação de aprovação em vez de inserir direto
    $data = json_encode([
        'proNome' => $proNome,
        'proDescricao' => $proDescricao,
        'proPreco' => $proPreco,
        'proQuantidadeEstoque' => $proQuant,
        'proCatID' => $proCatID,
        'catNome' => $catName,
        'proLojaID' => $proLojaID,
        'proFoto' => $foto1,
        'proFoto2' => $foto2,
        'proFoto3' => $foto3
    ]);

    // Prepara variáveis para bindParam (não pode usar expressões)
    $tipo = 'product';
    $vendedor_nome = $_SESSION['usuNome'] ?? 'Desconhecido';
    $vendedor_email = $_SESSION['usuEmail'] ?? 'desconhecido@email.com';
    $status = 'pending';

    $sql = "INSERT INTO approval_requests (type, vendID, vendedor_nome, vendedor_email, data_json, status) 
            VALUES (:tipo, :vendID, :nome, :email, :data, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);
    $stmt->bindParam(':nome', $vendedor_nome);
    $stmt->bindParam(':email', $vendedor_email);
    $stmt->bindParam(':data', $data);
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        ob_end_clean();
        header('Location: minhas-solicitacoes.php?novo=1&tipo=product');
        exit;
    } else {
        throw new Exception('Erro ao criar solicitação: ' . implode(' | ', $stmt->errorInfo()));
    }

} catch (Exception $e) {
    ob_end_clean();
    header('Location: pag-vender.php?erro=' . urlencode($e->getMessage()));
    exit;
}

?>