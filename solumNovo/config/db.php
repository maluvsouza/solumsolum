<?php 

class Database{
    private $host = 'br612.hostgator.com.br';
    private $db_name = 'hubsap45_bd_tcc_2025_solum';
    private $username = 'hubsap45_usrsolum';
    private $password = '$0!14@stRik3';
    public $conn;

    public function getConnection(){
        $this->conn = null;

        try{

            $this->conn = new PDO(
             "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
             $this->username, $this->password
            );
            $this->conn->exec("set names utf8"); 

        }catch(PDOException $error){
            echo "Opss!! Você não recebeu tudo". $error->getMessage();
        }
        return $this->conn;

    }
}



?>