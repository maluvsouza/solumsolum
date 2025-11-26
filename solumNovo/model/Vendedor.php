<?php 

require_once("Usuario.php");

class Vendedor extends Usuario{
    private $conn;
    private $table_name = "vendedores";

    //Atributos

public $vendID ;
public $vendUsuID;
public $vendCNPJ ;
    //Construtor

    public function __construct($db){
    
        $this->conn = $db;
    
    }

    //listar todos os valores da tbl produto

    public function readAll(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    
    
}
