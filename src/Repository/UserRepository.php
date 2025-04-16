<?php
namespace Src\Repository;

use Src\Models\User;
use PDO;

class UserRepository extends CrudRepository
{

    public function __construct()
    {
        parent::__construct(new User());
    }

    public function updateByStudent(int $id, array $data): bool
    {
        $fields = $this->model->getFields(); 
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
}