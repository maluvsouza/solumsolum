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
require_once "model/Loja.php";

// Verifica se usuário está logado
if (!isset($_SESSION['usuID'])) {
    echo "<script>
        alert('Você precisa estar logado para criar uma loja.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $lojNome = $_POST['loj-nome'] ?? '';
    $lojDescricao = $_POST['loj-descricao'] ?? '';

    // Validação básica
    if (empty($lojNome) || empty($lojDescricao)) {
        echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.location.href = 'pag-cadastro-loja.php';
        </script>";
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();

    try {
        // Obtém o vendID do usuário
        $getVendQuery = "SELECT vendID FROM vendedores WHERE vendUsuID = :usuID";
        $getVendStmt = $conn->prepare($getVendQuery);
        $getVendStmt->bindParam(':usuID', $_SESSION['usuID'], PDO::PARAM_INT);
        $getVendStmt->execute();

        if ($getVendStmt->rowCount() === 0) {
            echo "<script>
                alert('Você não é um vendedor registrado.');
                window.location.href = 'vender.php';
            </script>";
            exit;
        }

        $vendedor = $getVendStmt->fetch(PDO::FETCH_ASSOC);
        $vendID = $vendedor['vendID'];

        // Verifica se vendedor já tem loja
        $checkLojaQuery = "SELECT lojID FROM lojas WHERE lojVendedorID = :vendID";
        $checkLojaStmt = $conn->prepare($checkLojaQuery);
        $checkLojaStmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);
        $checkLojaStmt->execute();

        if ($checkLojaStmt->rowCount() > 0) {
            echo "<script>
                alert('Você já possui uma loja cadastrada.');
                window.location.href = 'pag-vender.php';
            </script>";
            exit;
        }

        // Verifica se vendedor tem loja pendente de aprovação
        $checkPendingQuery = "SELECT id FROM approval_requests WHERE type = 'store' AND vendID = :vendID AND status = 'pending'";
        $checkPendingStmt = $conn->prepare($checkPendingQuery);
        $checkPendingStmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);
        $checkPendingStmt->execute();

        if ($checkPendingStmt->rowCount() > 0) {
            echo "<script>
                alert('⏳ Você já tem uma loja aguardando aprovação!\\n\\nPor favor, aguarde a análise do nosso time de moderação antes de tentar criar outra loja.');
                window.location.href = 'pag-vender.php';
            </script>";
            exit;
        }

        // Processa upload da foto
        $lojFoto = null;
        if (isset($_FILES['loj-foto']) && $_FILES['loj-foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadDir = __DIR__ . '/assets/lojas';
            $resultado = uploadArquivo($_FILES['loj-foto'], $uploadDir);
            
            if (!$resultado['sucesso']) {
                echo "<script>
                    alert('Erro no upload da foto: " . htmlspecialchars($resultado['erro']) . "');
                    window.location.href = 'pag-cadastro-loja.php';
                </script>";
                exit;
            }
            $lojFoto = 'assets/lojas/' . $resultado['arquivo'];
        }

        // Cria solicitação de aprovação em vez de inserir direto
        $data = json_encode([
            'lojNome' => $lojNome,
            'lojDescricao' => $lojDescricao,
            'lojLogo' => $lojFoto
        ]);

        // Prepara variáveis para bindParam (não pode usar expressões)
        $tipo = 'store';
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
            header('Location: minhas-solicitacoes.php?novo=1&tipo=store');
            exit;
        } else {
            ob_end_clean();
            header('Location: pag-cadastro-loja.php?erro=1');
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Erro na conexão com o banco: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = 'pag-cadastro-loja.php';
        </script>";
        exit;
    }
} else {
    // Se não for POST, redireciona de volta
    header('Location: pag-cadastro-loja.php');
    exit;
}

?>
