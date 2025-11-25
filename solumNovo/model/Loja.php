<?php 

class Loja{
    private $conn;
    private $table_name = "lojas";

    //atributos

    public $lojID;
    public $lojNome;
    public $lojDescricao;
    public $lojLogo;
    public $lojVendedorID;

    //construtor

    public function __construct($db){

    $this -> conn = $db;

    }

    public function readAll(){
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt -> execute();
        return $stmt;
    }

    public function readById($id) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE lojID = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC); 
}

    /**
     * Filtra lojas dinamicamente.
     * Aceita keys em $opts: q (string buscar por nome), minProducts (int), sort (name_asc|name_desc|products_asc|products_desc)
     * Retorna array associativo de lojas com campo adicional `product_count`.
     */
    public function filter(array $opts = []) {
        $where = [];
        $params = [];

        if (!empty($opts['q'])) {
            $where[] = "l.lojNome LIKE :q";
            $params[':q'] = "%" . $opts['q'] . "%";
        }

        // Build base SQL including subquery for product count
        $sql = "SELECT l.*, (SELECT COUNT(*) FROM produtos p WHERE p.proLojaID = l.lojID) AS product_count FROM " . $this->table_name . " l";

        // minProducts will be represented as a where-clause on the main query using the subquery
        if (isset($opts['minProducts']) && $opts['minProducts'] !== '') {
            $where[] = "(SELECT COUNT(*) FROM produtos p WHERE p.proLojaID = l.lojID) >= :minProducts";
            $params[':minProducts'] = (int)$opts['minProducts'];
        }

        // Now attach WHERE once, using only conditions we collected for the main query
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        // Sorting
        $allowedSort = [
            'name_asc' => 'l.lojNome ASC',
            'name_desc' => 'l.lojNome DESC',
            'products_asc' => 'product_count ASC',
            'products_desc' => 'product_count DESC'
        ];

        if (!empty($opts['sort']) && isset($allowedSort[$opts['sort']])) {
            $sql .= " ORDER BY " . $allowedSort[$opts['sort']];
        } else {
            $sql .= " ORDER BY l.lojID DESC";
        }

        // If debug flag present, return SQL and params instead of executing
        if (!empty($opts['debug_sql'])) {
            return ['sql' => $sql, 'params' => $params];
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            if (is_int($v)) {
                $stmt->bindValue($k, $v, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($k, $v, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

