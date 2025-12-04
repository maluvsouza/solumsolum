<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

?>
<?php
// Página para alterar dados do usuário logado
session_start();

if (!isset($_SESSION['usuID'])) {
    header('Location: login.php');
    exit;
}

require_once("./utils/header.php");
if (isset($_SESSION['usuID'])) {
    // Usuário logado
    require_once("./utils/navbar_logado.php");
} else {
    // Usuário não logado
    require_once("./utils/navbar_nao-logado.php");
}

require_once __DIR__ . '/config/db.php';

$database = new Database();
$conn = $database->getConnection();

$usuID = $_SESSION['usuID'];

$stmt = $conn->prepare('SELECT usuID, usuNome, usuEmail, usuTelefone, usuCep FROM usuarios WHERE usuID = :id');
$stmt->bindParam(':id', $usuID, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Usuário não encontrado.";
    exit;
}

// Mensagem de sucesso/erro opcional via query string
$msg = $_GET['msg'] ?? '';
$err = $_GET['err'] ?? '';
?>

<main class="container">
    <h2>Alterar meus dados</h2>

    <?php if ($msg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($msg ?? '') ?></div>
    <?php endif; ?>
    <?php if ($err): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err ?? '') ?></div>
    <?php endif; ?>

    <form action="handlerUpdateUsuario.php" method="POST">
        <input type="hidden" name="usuID" value="<?= htmlspecialchars($user['usuID'] ?? '') ?>">

        <div class="campo-dados">
            <label for="nome">Nome</label><br>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($user['usuNome'] ?? '') ?>" required><br><br>
        </div>

        <div class="campo-dados">
            <label for="email">E-mail</label><br>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['usuEmail'] ?? '') ?>" required><br><br>
        </div>

        <div class="campo-dados">
            <label for="telefone">Telefone</label><br>
            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($user['usuTelefone'] ?? '') ?>"><br><br>
        </div>

        <div class="campo-dados">
            <label for="cep">CEP</label><br>
            <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($user['usuCep'] ?? '') ?>"><br><br>
        </div>

        <div class="campo-dados">
            <label for="senha">Nova senha (deixe em branco para manter a atual)</label><br>
            <input type="password" id="senha" name="senha"><br><br>
        </div>

        <button type="submit">Salvar alterações</button>
    </form>

    <p><a href="index.php">Voltar ao início</a></p>
</main>

<?php require_once __DIR__ . '/utils/footer.php'; ?>

</body>

</html>