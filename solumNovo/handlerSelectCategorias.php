<?php

require_once "config/db.php";
require_once "model/Categoria.php";

//Conexão com BD

$database = new Database();
$db = $database->getConnection();

//Instância da objeto Produto

$categoria = new Categoria($db);

$stmt = $categoria->readAll();
$num = $stmt->rowCount();


?>