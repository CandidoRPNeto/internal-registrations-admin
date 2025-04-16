<?php
namespace Src\Repository;

use Src\Models\Enrollments;
use PDO;

class EnrollmentsRepository extends CrudRepository
{

    public function __construct()
    {
        parent::__construct(new Enrollments());
    }

    public function listAll($page, $search = []): array
    {
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $where = '';
        $params = [];

        if (!empty($search['username'])) {
            $where = 'WHERE u.name LIKE :name';
            $params[':name'] = '%' . $search['username'] . '%';
        } else if (!empty($search['classname'])) {
            $where = 'WHERE c.name LIKE :name';
            $params[':name'] = '%' . $search['classname'] . '%';
        }

        $countSql = "SELECT COUNT(*) AS total 
                    FROM enrollments e
                    JOIN classrooms c ON e.classroom_id = c.id
                    JOIN students s ON e.student_id = s.id
                    JOIN users u ON s.user_id = u.id
                     $where";

        $countStmt = $this->conn->prepare($countSql);
        $countStmt->execute($params);
        $totalItems = (int) $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        $sql = "SELECT 
                    e.id,
                    u.name as student,
                    c.name as classroom,
                    e.created_at
                    FROM enrollments e
                    JOIN classrooms c ON e.classroom_id = c.id
                    JOIN students s ON e.student_id = s.id
                    JOIN users u ON s.user_id = u.id
                $where
                ORDER BY e.created_at DESC
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