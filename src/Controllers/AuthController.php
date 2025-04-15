<?php
namespace Src\Controllers;

use Src\Repository\AuthRepository;
use Src\Request\LoginRequest;

class AuthController
{
    protected $repository;
    public function __construct()
    {
        $this->repository = new AuthRepository();
    }
    public function login($data): array
    {
        $login = $this->repository->login($data['email'], $data['password']);
        if (LoginRequest::validate($data) && $login) {
            return [
                'code' => 200,
                'data' => $login
            ];
        }
        return [
            'code' => 405,
            'data' => ['message' => 'login ou senha incorretos']
        ];
    }
}