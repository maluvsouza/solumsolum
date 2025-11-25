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

require_once "config/db.php";
require_once "utils/upload.php";

// Verifica se usuário está logado
if (!isset($_SESSION['usuID'])) {
    echo "<script>
        alert('Você precisa estar logado.');
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
            window.location.href = 'pag-vender.php';
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

        // Obtém dados atuais da loja
        $getLojaQuery = "SELECT * FROM lojas WHERE lojVendedorID = :vendID";
        $getLojaStmt = $conn->prepare($getLojaQuery);
        $getLojaStmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);
        $getLojaStmt->execute();

        if ($getLojaStmt->rowCount() === 0) {
            echo "<script>
                alert('Loja não encontrada.');
                window.location.href = 'pag-vender.php';
            </script>";
            exit;
        }

        $lojaAtual = $getLojaStmt->fetch(PDO::FETCH_ASSOC);
        $lojFoto = $lojaAtual['lojLogo']; // Mantém a foto atual como padrão

        // Processa upload da foto se fornecida
        if (isset($_FILES['loj-foto']) && $_FILES['loj-foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadDir = __DIR__ . '/assets/lojas';
            $resultado = uploadArquivo($_FILES['loj-foto'], $uploadDir);
            
            if (!$resultado['sucesso']) {
                echo "<script>
                    alert('Erro no upload da foto: " . htmlspecialchars($resultado['erro']) . "');
                    window.location.href = 'pag-vender.php';
                </script>";
                exit;
            }

            // Deleta foto antiga se existir
            if ($lojaAtual['lojLogo']) {
                $caminhoFotoAntiga = $uploadDir . '/' . $lojaAtual['lojLogo'];
                if (file_exists($caminhoFotoAntiga)) {
                    unlink($caminhoFotoAntiga);
                }
            }

            $lojFoto = $resultado['arquivo'];
        }

        // Atualiza a loja
        $sql = "UPDATE lojas SET lojNome = :nome, lojDescricao = :descricao, lojLogo = :logo WHERE lojVendedorID = :vendID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':nome', $lojNome);
        $stmt->bindParam(':descricao', $lojDescricao);
        $stmt->bindParam(':logo', $lojFoto);
        $stmt->bindParam(':vendID', $vendID, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "<script>
                alert('Dados da loja atualizados com sucesso!');
                window.location.href = 'pag-vender.php';
            </script>";
            exit;
        } else {
            echo "<script>
                alert('Erro ao atualizar loja: " . implode(" | ", $stmt->errorInfo()) . "');
                window.location.href = 'pag-vender.php';
            </script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Erro na conexão com o banco: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = 'pag-vender.php';
        </script>";
        exit;
    }
} else {
    // Se não for POST, redireciona de volta
    header('Location: pag-vender.php');
    exit;
}

?>
