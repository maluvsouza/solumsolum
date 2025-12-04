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

require_once("./utils/header.php");
if (isset($_SESSION['usuID'])) {

    require_once("./utils/navbar_logado.php");
} else {

    require_once("./utils/navbar_nao-logado.php");
}

if (!isset($_SESSION['usuID'])) {
    echo "<script>
        alert('Você precisa estar logado para vender.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

?>
<h1 class="titulo">Cadastro de Vendedor</h1>

<p class="descricao">
    Insira é o tipo e número de identificação do titular da conta.<br>
</p>


<div class="vendedor-container">
    <form class="row m-2 form-vendedor" method="POST" action="handlerInsertVendedor.php">

        <div class="col-md">
            <label for="inputState" class="form-label">Tipo de documento</label>
            <select name="tipo" class="form-select" required>
                <option value="CPF" selected>CPF</option>
                <option value="CNPJ">CNPJ</option>
            </select>
        </div>

        <div class="col-md">
            <label for="inputCep" class="form-label">Número do documento</label>
            <input type="text" class="form-control" name="num-doc" required>
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-vendedor">Prosseguir</button>
        </div>

    </form>
</div>

<?PHP require_once("./utils/footer.php"); ?>