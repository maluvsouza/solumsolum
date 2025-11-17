<?php 



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "config/db.php";
require_once "model/Usuario.php";

$database = new Database();
$conn = $database->getConnection();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $usuNome = $_POST['nome'];
    $usuEmail = $_POST['email'];
    $usuSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT); 
    $usuCep = $_POST['cep'];
    $usuTelefone = $_POST['telefone'];
   

   
    $sql = "INSERT INTO usuarios (usuNome, usuEmail, usuSenha, usuCep, usuTelefone)
            VALUES (:nome, :email, :senha, :cep, :telefone)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':nome', $usuNome);
    $stmt->bindParam(':email', $usuEmail);
    $stmt->bindParam(':senha', $usuSenha); 
      $stmt->bindParam(':cep', $usuCep); 
        $stmt->bindParam(':telefone', $usuTelefone); 
        

    

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Usuário cadastrado com sucesso! <a href='index.php'> Voltar. </a></div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao cadastrar: " . implode(" | ", $stmt->errorInfo()) . "</div>";
    }
}
?>
