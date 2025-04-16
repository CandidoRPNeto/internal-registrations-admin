<?php
namespace Src\Repository;

use Src\Models\Model;
use PDO;
use Database;

class CrudRepository
{
    protected PDO $conn;

    public function __construct(protected Model $model)
    {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function count($where = [])
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->model->getTableName()}";
        $params = [];
    
        if (!empty($where)) {
            $conditions = [];
            foreach ($where as $column => $value) {
                $conditions[] = "{$column} = :{$column}";
                $params[$column] = $value;
            }
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
    
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
    
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    }

    public function create(array $data)
    {
        $fields = $this->model->getFields();
        $columns = implode(', ', $fields);
        $placeholders = ':' . implode(', :', $fields);

        $sql = "INSERT INTO {$this->model->getTableName()} ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        foreach ($fields as $field) {
            $stmt->bindValue(":$field", $data[$field] ?? null);
        }

        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function read(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->model->getTableName()} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $fields = $this->model->getFields();
        
        $validFields = array_filter($fields, fn($f) => array_key_exists($f, $data));
    
        if (empty($validFields)) {
            return false;
        }
        
        $set = implode(', ', array_map(fn($f) => "$f = :$f", $validFields));
        $sql = "UPDATE {$this->model->getTableName()} SET $set WHERE id = :id";
    
        $stmt = $this->conn->prepare($sql);
    
        foreach ($validFields as $field) {
            $stmt->bindValue(":$field", $data[$field]);
        }
    
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->model->getTableName()} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    public function listAll($page): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $countSql = "SELECT COUNT(*) AS total FROM {$this->model->getTableName()}";
        $countStmt = $this->conn->query($countSql);
        $totalItems = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $sql = "SELECT * FROM {$this->model->getTableName()} 
                ORDER BY {$this->model->getOrdenation()}
                LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalPages = (int) ceil($totalItems / $limit);

        return [
            'items' => $items,
            'total_pages' => $totalPages,
        ];
    }

}
