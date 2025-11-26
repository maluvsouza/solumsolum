<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "config/db.php";
require_once "model/Usuario.php";

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuNome     = $_POST['nome'];
    $usuEmail    = $_POST['email'];
    $usuSenha    = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $usuCep      = $_POST['cep'];
    $usuTelefone = $_POST['telefone'];

    $sql = "INSERT INTO usuarios (usuNome, usuEmail, usuSenha, usuCep, usuTelefone)
            VALUES (:nome, :email, :senha, :cep, :telefone)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nome', $usuNome);
    $stmt->bindParam(':email', $usuEmail);
    $stmt->bindParam(':senha', $usuSenha);
    $stmt->bindParam(':cep', $usuCep);
    $stmt->bindParam(':telefone', $usuTelefone);

    try {
        if ($stmt->execute()) {
            // Cadastro realizado com sucesso
            header("Location: index.php?cadastro=sucesso");
            exit;
        }
    } catch (PDOException $e) {
        // Verifica se é erro de entrada duplicada (email já cadastrado)
        if ($e->getCode() == 23000) {
            header("Location: index.php?cadastro=duplicado");
            exit;
        } else {
            // Qualquer outro erro
            header("Location: index.php?cadastro=erro");
            exit;
        }
    }
}
