<?php
namespace Src\Controllers;

use Src\Repository\{UserRepository, StudentRepository};

class StudentController
{
    protected $studentRepository;
    protected $userRepository;

    public function __construct()
    {
        $this->studentRepository = new StudentRepository();
        $this->userRepository = new UserRepository();
    }

    public function create($data)
    {
        $data['role'] = 'STD';
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $user = $this->userRepository->create($data);
        $data['user_id'] = $user;
        $student = $this->studentRepository->create($data);
        return [
            'code' => 200,
            'data' => $student
        ];
    }

    public function update($id, $data)
    {
        $student = $this->studentRepository->update($id, $data);
        
        $user = $this->userRepository->updateByStudent($id, $data);
        return [
            'code' => 200,
            'data' => $student
        ];
    }

    public function show($id)
    {
        return $this->studentRepository->read($id);
    }

    public function search($page, $search)
    {
        return $this->studentRepository->listAll($page, ['name' => $search]);
    }

    public function index($page)
    {
        return $this->studentRepository->listAll($page);
    }

    public function delete($id)
    {
        return $this->studentRepository->delete($id);
    }
}