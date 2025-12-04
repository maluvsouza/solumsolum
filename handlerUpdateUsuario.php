<?php
session_start();

if (!isset($_SESSION['usuID'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/config/db.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: alterar-dados.php');
    exit;
}

$usuID_session = $_SESSION['usuID'];
$usuID = isset($_POST['usuID']) ? intval($_POST['usuID']) : 0;

// Só permite o usuário alterar seus próprios dados
if ($usuID === 0 || $usuID !== intval($usuID_session)) {
    header('Location: alterar-dados.php?err=' . urlencode('Acesso não autorizado'));
    exit;
}

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$cep = trim($_POST['cep'] ?? '');
$senha = $_POST['senha'] ?? '';

if ($nome === '' || $email === '') {
    header('Location: alterar-dados.php?err=' . urlencode('Nome e e-mail são obrigatórios'));
    exit;
}

try {
    if ($senha !== '') {
        // Atualiza incluindo senha
        $hashed = password_hash($senha, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET usuNome = :nome, usuEmail = :email, usuTelefone = :telefone, usuCep = :cep, usuSenha = :senha WHERE usuID = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':senha', $hashed);
    } else {
        // Atualiza sem tocar na senha
        $sql = "UPDATE usuarios SET usuNome = :nome, usuEmail = :email, usuTelefone = :telefone, usuCep = :cep WHERE usuID = :id";
        $stmt = $conn->prepare($sql);
    }

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':id', $usuID, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Atualiza nome na sessão
        $_SESSION['usuNome'] = $nome;
        header('Location: alterar-dados.php?msg=' . urlencode('Dados atualizados com sucesso'));
        exit;
    } else {
        $error = implode(' | ', $stmt->errorInfo());
        header('Location: alterar-dados.php?err=' . urlencode('Erro ao atualizar: ' . $error));
        exit;
    }
} catch (Exception $e) {
    header('Location: alterar-dados.php?err=' . urlencode('Exceção: ' . $e->getMessage()));
    exit;
}
