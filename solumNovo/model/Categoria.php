<?php 

class Categoria{
    private $conn;
    private $table_name = "categoria";

    //Atributos

    public $catID;
    public $catNome;
    public $catDescricao;
   
   
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

   public function getCategoryByID($catID) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE catID = :catID";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':catID', $catID, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    
}
