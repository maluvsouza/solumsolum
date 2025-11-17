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
require_once "model/Vendedor.php";

// Verifica se usuário está logado
if (!isset($_SESSION['usuID'])) {
    echo "<script>
        alert('Você precisa estar logado para se registrar como vendedor.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $tipo = $_POST['tipo'] ?? '';
    $numDoc = $_POST['num-doc'] ?? '';
    $usuID = $_SESSION['usuID'];

    // Validação básica
    if (empty($tipo) || empty($numDoc)) {
        echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.location.href = 'vender.php';
        </script>";
        exit;
    }

    $database = new Database();
    $conn = $database->getConnection();

    try {
        // Verifica se usuário já é vendedor
        $checkQuery = "SELECT vendID FROM vendedores WHERE vendUsuID = :usuID";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bindParam(':usuID', $usuID, PDO::PARAM_INT);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            echo "<script>
                alert('Você já está registrado como vendedor.');
                window.location.href = 'pag-vender.php';
            </script>";
            exit;
        }

        // Se tipo é CNPJ, armazena o número; senão deixa vazio
        $vendCNPJ = ($tipo === 'CNPJ') ? $numDoc : null;

        // Insere novo vendedor
        $sql = "INSERT INTO vendedores (vendUsuID, vendCNPJ) VALUES (:usuID, :cnpj)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuID', $usuID, PDO::PARAM_INT);
        $stmt->bindParam(':cnpj', $vendCNPJ);

        if ($stmt->execute()) {
            echo "<script>
                alert('Parabéns! Você foi registrado como vendedor com sucesso! Agora você pode criar sua loja.');
                window.location.href = 'pag-cadastro-loja.php';
            </script>";
            exit;
        } else {
            echo "<script>
                alert('Erro ao registrar vendedor: " . implode(" | ", $stmt->errorInfo()) . "');
                window.location.href = 'vender.php';
            </script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Erro na conexão com o banco: " . htmlspecialchars($e->getMessage()) . "');
            window.location.href = 'vender.php';
        </script>";
        exit;
    }
} else {
    // Se não for POST, redireciona de volta
    header('Location: vender.php');
    exit;
}

?>
