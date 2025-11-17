<?php

require_once "config/db.php";
require_once "model/Estado.php";

//Conexão com BD

$database = new Database();
$db = $database->getConnection();

//Instância da objeto Produto

$estado = new Estado($db);

$stmt = $estado->readAll();
$num = $stmt->rowCount();


?>