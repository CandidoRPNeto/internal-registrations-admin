<?php
namespace Src\Repository;

use Src\Models\Model;
use PDO;
use Database;

class CrudRepository
{
    private PDO $conn;

    public function __construct(private Model $model)
    {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $this->conn = $database->getConnection();
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
        $set = implode(', ', array_map(fn($f) => "$f = :$f", $fields));

        $sql = "UPDATE {$this->model->getTableName()} SET $set WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        foreach ($fields as $field) {
            $stmt->bindValue(":$field", $data[$field] ?? null);
        }

        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->model->getTableName()} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }

    public function listAll(): array
    {
        $sql = "SELECT * FROM {$this->model->getTableName()} ORDER BY {$this->model->getNameField()} ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
