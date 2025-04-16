<?php
namespace Src\Repository;

use Src\Models\{Student, User};
use PDO;

class StudentRepository extends CrudRepository
{

    public function __construct()
    {
        parent::__construct(new Student());
    }

    public function read(int $id): ?array
    {
        $sql = "SELECT 
                    s.id,
                    u.email,
                    u.name,
                    s.cpf,
                    s.birth_date,
                    (
                        SELECT COUNT(*) 
                        FROM enrollments e 
                        WHERE e.student_id = s.id
                    ) AS enrollments_count
                FROM students s
                INNER JOIN users u ON s.user_id = u.id 
                WHERE s.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function listAll($page, $search = []): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $where = '';
        $params = [];
    
        if (!empty($search['name'])) {
            $where = 'WHERE u.name LIKE :name';
            $params[':name'] = '%' . $search['name'] . '%';
        }
    
        $countSql = "SELECT COUNT(*) AS total 
                     FROM students s
                     INNER JOIN users u ON s.user_id = u.id
                     $where";
        
        $countStmt = $this->conn->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    
        $sql = "SELECT 
                    s.id,
                    u.email,
                    u.name,
                    s.cpf,
                    s.birth_date,
                    (
                        SELECT COUNT(*) 
                        FROM enrollments e 
                        WHERE e.student_id = s.id
                    ) AS enrollments_count
                FROM students s
                INNER JOIN users u ON s.user_id = u.id
                $where
                ORDER BY u.name ASC
                LIMIT :limit OFFSET :offset";
    
        $stmt = $this->conn->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, PDO::PARAM_STR);
        }
    
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