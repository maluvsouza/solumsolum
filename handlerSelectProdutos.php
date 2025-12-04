<?php

require_once "config/db.php";
require_once "model/Produto.php";
require_once "model/Categoria.php";
require_once "model/Loja.php";

//Conexão com BD

$database = new Database();
$db = $database->getConnection();

//Instância da objeto Produto

$produto = new Produto($db);

// load categories and lojas for filters
$categoriaModel = new Categoria($db);
$categoriasStmt = $categoriaModel->readAll();
$categorias = $categoriasStmt->fetchAll(PDO::FETCH_ASSOC);

$lojaModel = new Loja($db);
$lojasStmt = $lojaModel->readAll();
$lojas = $lojasStmt->fetchAll(PDO::FETCH_ASSOC);

// Read filter params from GET
$filters = [];
if (isset($_GET['q'])) $filters['q'] = trim($_GET['q']);
if (isset($_GET['catID']) && $_GET['catID'] !== '') $filters['catID'] = (int)$_GET['catID'];
if (isset($_GET['lojID']) && $_GET['lojID'] !== '') $filters['lojID'] = (int)$_GET['lojID'];
if (isset($_GET['minPrice'])) $filters['minPrice'] = $_GET['minPrice'];
if (isset($_GET['maxPrice'])) $filters['maxPrice'] = $_GET['maxPrice'];
if (isset($_GET['sort'])) $filters['sort'] = $_GET['sort'];

try {
    $produtos = $produto->filter($filters);
} catch (PDOException $error) {
    echo "Erro na consulta: " . $error->getMessage();
    $produtos = [];
}





?>