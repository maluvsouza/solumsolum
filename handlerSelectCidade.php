<?php

require_once "config/db.php";
require_once "model/Cidade.php";

//Conexão com BD

$database = new Database();
$db = $database->getConnection();

//Instância da objeto Produto

$cidade = new Cidade($db);

$stmt = $cidade->readAll();
$num = $stmt->rowCount();


?>