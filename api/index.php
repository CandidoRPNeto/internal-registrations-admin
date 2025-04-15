<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Src\Controllers\{AuthController, ClassroomController, StudentController};
use Src\Helpers\JwtHelper;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));
$method = $_SERVER['REQUEST_METHOD'];

$resource = $uri[1] ?? null;
$id = $uri[2] ?? null;

switch ($resource) {
    case 'auth':
        if ($method != 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
        }
        $controller = new AuthController();
        $data = json_decode(file_get_contents("php://input"), true);
        $response = $controller->login($data);
        http_response_code($response['code']);
        echo json_encode($response['data']);
        break;
    case 'classroom':
        authMiddleware();
        crudResource($method, new ClassroomController(), $id);
        break;
    case 'student':
        authMiddleware();
        crudResource($method, new StudentController(), $id);
        break;
    default:
        http_response_code(404);
        echo json_encode(['message' => 'Rota não encontrada']);
        exit;
}

function authMiddleware(): void
{
    $headers = getallheaders();

    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Token ausente']);
        exit;
    }

    $jwt = str_replace('Bearer ', '', $headers['Authorization']);
    $decoded = JwtHelper::verifyToken($jwt);

    if (!$decoded) {
        http_response_code(401);
        echo json_encode(['error' => 'Token inválido ou expirado']);
        exit;
    }

    if ($decoded['role'] !== 'ADM') {
        http_response_code(403);
        echo json_encode(['error' => 'Acesso negado']);
        exit;
    }
}

function crudResource($method, $controller, $id): void
{
    switch ($method) {
        case 'GET':
            echo json_encode($id ? $controller->show($id) : $controller->index());
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($controller->create($data));
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($controller->update($id, $data));
            break;

        case 'DELETE':
            echo json_encode($controller->delete($id));
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Método não permitido']);
    }
}
