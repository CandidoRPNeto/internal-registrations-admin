<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Src\Controllers\{AuthController, ClassroomController, DashboardController, EnrollmentsController, StudentController};
use Src\Helpers\JwtHelper;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($route, '/'));
$resource = $uri[1] ?? null;
$id = $uri[2] ?? null;
$method = $_SERVER['REQUEST_METHOD'];


function handleResponse(): void
{
    try {
        $return = getRoute();
        http_response_code($return['code']);
        echo json_encode($return['data']);
    } catch (\Throwable $th) {
        http_response_code(500);
        echo json_encode([
            'message' => 'Houve um problema no backend',
            'error' => $th->getMessage()
        ]);
        exit;
    }
}
function getRoute()
{
    global $route, $method;
    $cleanRoute = str_replace('/api', '', $route);
    $response = [];
    switch ($cleanRoute) {
        case '/auth/login':
            if ($method != 'POST') {
                return [
                    'code' => 405,
                    'message' => 'Método não permitido'
                ];
            }
            $controller = new AuthController();
            $data = json_decode(file_get_contents("php://input"), true);
            $response = $controller->login($data);
            break;
        case '/dashboard/quantity':
            $controller = new DashboardController();
            $response = $controller->getInfo();
            break;
        default:
            $response = getResource();
    }
    return $response;
}

function getResource(): array
{
    global $resource;
    $response = [];
    switch ($resource) {
        case 'classroom':
            authMiddleware();
            $response = crudResource(new ClassroomController());
            break;
        case 'student':
            authMiddleware();
            $response = crudResource(new StudentController());
            break;
        case 'enrollment':
            authMiddleware();
            $response = crudResource(new EnrollmentsController());
            break;
        default:
            $response = [
                'code' => 404,
                'data' => ['message' => 'Rota não encontrada']
            ];
    }
    return $response;
}

function crudResource($controller): array
{
    global $method, $id;
    $response = ['code' => 200];
    switch ($method) {
        case 'GET':
            $page = $_GET['page'] ?? "1";
            $search = $_GET['search'] ?? false;
            $filter = $_GET['filter'] ?? false;
            $response['data'] = $id ? $controller->show($id) : ($search ? $controller->search($page, $search, $filter) : $controller->index($page));
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $response['data'] = $controller->create($data);
            break;
        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            $response['data'] = $controller->update($id, $data);
            break;
        case 'DELETE':
            $response['data'] = $controller->delete($id);
            break;
        default:
            $response = [
                'code' => 405,
                'data' => ['message' => 'Método não permitido']
            ];
    }
    return $response;
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

handleResponse();