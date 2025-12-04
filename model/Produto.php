<?php

require_once("Loja.php");

class Produto extends Loja
{
    private $conn;
    private $table_name = "produtos";

    //Atributos

    public $proID;
    public $proNome;
    public $proFoto;
    public $proFoto2;
    public $proFoto3;
    public $proDescricao;
    public $proPreco;
    public $proQuantidadeEstoque;
    public $proCatID;
    public $proLojaID;


    //Construtor

    public function __construct($db)
    {

        $this->conn = $db;
    }

    //listar todos os valores da tbl produto

    public function readAll()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getProduct()
    {
        $query = "SELECT * FROM  . $this->table_name . WHERE proID = :proID";
    }


    public function getLoja($proLojaID)
    {
        $query = "SELECT lojNome FROM lojas WHERE lojID = :proLojaID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':proLojaID', $proLojaID);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['lojNome'] : 'Loja não encontrada';
    }

    public function getLatest()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY proID DESC LIMIT 4";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Count products for a given store
    public function countByLoja($lojID)
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE proLojaID = :lojID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lojID', $lojID, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    }

    // Get latest products for a specific store
    public function latestByLoja($lojID, $limit = 3)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE proLojaID = :lojID ORDER BY proID DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lojID', $lojID, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all products for a specific store
    public function allByLoja($lojID)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE proLojaID = :lojID ORDER BY proID DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':lojID', $lojID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public function buscarProdutos($termo)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE proNome LIKE :termo";
        $stmt = $this->conn->prepare($query);
        $termo = "%" . $termo . "%";
        $stmt->bindParam(':termo', $termo);
        $stmt->execute();
        return $stmt;
    }

    public function getFavoritos($ids)
    {
        if (empty($ids)) return [];

        // Corrige índices quebrados do array
        $ids = array_values($ids);

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $query = "SELECT * FROM " . $this->table_name . " WHERE proID IN ($placeholders)";
        $stmt = $this->conn->prepare($query);

        foreach ($ids as $index => $id) {
            $stmt->bindValue($index + 1, $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function readByCategory($catID)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE proCatID = :catID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':catID', $catID);
        $stmt->execute();
        return $stmt;
    }

    // Dynamic filter: accepts array with keys: catID, lojID, minPrice, maxPrice, q, sort
    public function filter(array $opts = [])
    {
        $where = [];
        $params = [];

        if (!empty($opts['q'])) {
            $where[] = "proNome LIKE :q";
            $params[':q'] = "%" . $opts['q'] . "%";
        }

        if (!empty($opts['catID'])) {
            $where[] = "proCatID = :catID";
            $params[':catID'] = (int)$opts['catID'];
        }

        if (!empty($opts['lojID'])) {
            $where[] = "proLojaID = :lojID";
            $params[':lojID'] = (int)$opts['lojID'];
        }

        if (isset($opts['minPrice']) && $opts['minPrice'] !== '') {
            $where[] = "proPreco >= :minPrice";
            $params[':minPrice'] = (float)$opts['minPrice'];
        }

        if (isset($opts['maxPrice']) && $opts['maxPrice'] !== '') {
            $where[] = "proPreco <= :maxPrice";
            $params[':maxPrice'] = (float)$opts['maxPrice'];
        }

        $sql = "SELECT * FROM " . $this->table_name;
        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        // Sorting
        $allowedSort = [
            'price_asc' => 'proPreco ASC',
            'price_desc' => 'proPreco DESC',
            'newest' => 'proID DESC',
            'oldest' => 'proID ASC'
        ];

        if (!empty($opts['sort']) && isset($allowedSort[$opts['sort']])) {
            $sql .= " ORDER BY " . $allowedSort[$opts['sort']];
        } else {
            $sql .= " ORDER BY proID DESC";
        }

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            if (is_int($v)) {
                $stmt->bindValue($k, $v, PDO::PARAM_INT);
            } elseif (is_float($v)) {
                $stmt->bindValue($k, $v);
            } else {
                $stmt->bindValue($k, $v, PDO::PARAM_STR);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
