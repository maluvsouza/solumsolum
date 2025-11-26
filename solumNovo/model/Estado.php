<?php

class Estado {
    private $conn;
    private $table_name = "estado";

    public $estId;
    public $estNome;
    public $estSigla;
    

    
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
    
       