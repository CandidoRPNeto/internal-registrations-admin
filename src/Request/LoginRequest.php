<?php
namespace Src\Request;

class LoginRequest
{

    public static function validate($data)
    {
        $getStatus = self::getRuleError($data);
        if ($getStatus) {
            http_response_code(500);
            echo json_encode(['message' => $getStatus]);
            exit;
        }
    }

    public static function getRuleError($data): bool|string
    {
        if (!isset($data["password"]) || !isset($data["email"])) {
            return 'Precisa passar o email e a senha';
        }
        return false;
    }

}