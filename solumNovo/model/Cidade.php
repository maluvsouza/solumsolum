<?php 

require_once("Estado.php");

class Cidade extends Estado{
    private $conn;
    private $table_name = "cidade";

public $cidId;
public $cidNome;
public $cidUF;
    
 
   
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

    
    public function getEstado($cidUF) {
    $query = "SELECT estNome FROM estado WHERE estID = :cidUF";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':cidUF', $estID);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['estNome'] : 'Estado não encontrado';
}

}