<?php 

class Usuario{
    private $conn;
    private $table_name = "usuarios";

    //Atributos

public $usuID ;
public $usuNome ;
public $usuEmail ;
public $usuSenha ;
public $usuCep;
public $usuTelefone;
// public $usuEndereco ;
public $usuCID;
   
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
