<?php
namespace Src\Repository;

use Src\Models\Classroom;
use PDO;

class ClassroomRepository extends CrudRepository
{

    public function __construct()
    {
        parent::__construct(new Classroom());
    }

    public function listAll($page): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $countSql = "SELECT COUNT(*) AS total FROM {$this->model->getTableName()}";
        $countStmt = $this->conn->query($countSql);
        $totalItems = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $sql = "SELECT 
                    c.*, 
                    (
                        SELECT COUNT(*) 
                        FROM enrollments e 
                        WHERE e.classroom_id = c.id
                    ) AS students_count
                FROM classrooms c 
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