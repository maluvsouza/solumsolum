<?php
require_once __DIR__ . '/../admin/auth.php';
requireAdmin();
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: requests.php');
    exit;
}

$request_id = (int)($_POST['request_id'] ?? 0);
$action = $_POST['action'] ?? '';
$note = $_POST['note'] ?? '';

if (!$request_id || !in_array($action, ['approve', 'reject'])) {
    header('Location: requests.php?error=invalid');
    exit;
}

$database = new Database();
$conn = $database->getConnection();

try {
    // Fetch the request
    $stmt = $conn->prepare('SELECT * FROM approval_requests WHERE id = :id');
    $stmt->bindParam(':id', $request_id, PDO::PARAM_INT);
    $stmt->execute();
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$request) {
        throw new Exception('Solicitação não encontrada');
    }

    if ($action === 'approve') {
        $data = json_decode($request['data_json'], true);

        if ($request['type'] === 'store') {
            // Insere a loja
            $sql = "INSERT INTO lojas (lojNome, lojDescricao, lojLogo, lojVendedorID) 
                    VALUES (:nome, :descricao, :logo, :vendID)";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bindParam(':nome', $data['lojNome']);
            $stmt2->bindParam(':descricao', $data['lojDescricao']);
            $stmt2->bindParam(':logo', $data['lojLogo']);
            $stmt2->bindParam(':vendID', $request['vendID'], PDO::PARAM_INT);
            $stmt2->execute();
        } elseif ($request['type'] === 'product') {
            // Insere o produto
            $sql = "INSERT INTO produtos (proNome, proFoto, proFoto2, proFoto3, proDescricao, proPreco, proQuantidadeEstoque, proCatID, proLojaID)
                    VALUES (:nome, :foto, :foto2, :foto3, :descricao, :preco, :quant, :cat, :loja)";
            $stmt2 = $conn->prepare($sql);
            $stmt2->bindParam(':nome', $data['proNome']);
            $stmt2->bindParam(':foto', $data['proFoto']);
            $stmt2->bindParam(':foto2', $data['proFoto2']);
            $stmt2->bindParam(':foto3', $data['proFoto3']);
            $stmt2->bindParam(':descricao', $data['proDescricao']);
            $stmt2->bindParam(':preco', $data['proPreco']);
            $stmt2->bindParam(':quant', $data['proQuantidadeEstoque'], PDO::PARAM_INT);
            $stmt2->bindParam(':cat', $data['proCatID'], PDO::PARAM_INT);
            $stmt2->bindParam(':loja', $data['proLojaID'], PDO::PARAM_INT);
            $stmt2->execute();
        }

        // Update request status
        $status = 'approved';
        $stmt3 = $conn->prepare('UPDATE approval_requests SET status = :status, admin_note = :note WHERE id = :id');
        $stmt3->bindParam(':status', $status);
        $stmt3->bindParam(':note', $note);
        $stmt3->bindParam(':id', $request_id, PDO::PARAM_INT);
        $stmt3->execute();

    } elseif ($action === 'reject') {
        // Update request status
        $status = 'rejected';
        $stmt3 = $conn->prepare('UPDATE approval_requests SET status = :status, admin_note = :note WHERE id = :id');
        $stmt3->bindParam(':status', $status);
        $stmt3->bindParam(':note', $note);
        $stmt3->bindParam(':id', $request_id, PDO::PARAM_INT);
        $stmt3->execute();
    }

    header('Location: requests.php?success=1');
    exit;

} catch (Exception $e) {
    header('Location: requests.php?error=' . urlencode($e->getMessage()));
    exit;
}
