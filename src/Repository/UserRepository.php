<?php
namespace Src\Repository;

use PDO;

class UserRepository extends CrudRepository
{

    protected string $table = "users";

    protected array $fields = [
        'name',
        'email',
        'password',
        'role'
    ];
    
    public function updateByStudent(int $id, array $data): bool
    {
        $fields = $this->fields; 
        $validFields = array_filter($fields, fn($f) => array_key_exists($f, $data));
    
        if (empty($validFields)) {
            return false;
        }
    
        $set = implode(', ', array_map(fn($f) => "u.$f = :$f", $validFields));
        $sql = "UPDATE users u
                JOIN students s ON u.id = s.user_id
                SET $set
                WHERE s.id = :student_id";
    
        $stmt = $this->conn->prepare($sql);
    
        foreach ($validFields as $field) {
            $stmt->bindValue(":$field", $data[$field]);
        }
    
        $stmt->bindValue(':student_id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    
    public function listAll($page): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $countSql = "SELECT COUNT(*) AS total FROM {$this->table}";
        $countStmt = $this->conn->query($countSql);
        $totalItems = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $sql = "SELECT id, name, email, role FROM {$this->table} 
                ORDER BY name ASC
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

    public function read(int $id): ?array
    {
        $sql = "SELECT id, name, email, role FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}