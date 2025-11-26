<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
session_start();

require_once './config/db.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuEmail = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['usuSenha'])) {
        $_SESSION['usuID'] = $usuario['usuID'];
        $_SESSION['usuNome'] = $usuario['usuNome'];

        if (isset($usuario['usuTipo'])) {
            $_SESSION['usuTipo'] = $usuario['usuTipo'];
        }

        header("Location: index.php");
        exit;
    } else {

        $_SESSION['erroLogin'] = "E-mail ou senha inválidos.";
        header("Location: login.php");
        exit;
    }
}
?>