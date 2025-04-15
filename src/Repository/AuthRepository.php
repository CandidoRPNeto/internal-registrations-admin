<?php
namespace Src\Repository;

use PDO;
use Src\Models\User;
use Database;
use Src\Helpers\JwtHelper;

class AuthRepository
{
    private PDO $conn;
    private User $model;

    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->model = new User();
    }

    public function login(string $email, string $password): array|false
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->model->getTableName() . " WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return [
                'message' => 'usuario logado com sucesso',
                'token' => JwtHelper::generateToken($user)
            ];
        }
        return false;
    }
}
