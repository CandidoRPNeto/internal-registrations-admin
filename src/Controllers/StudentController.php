<?php
namespace Src\Controllers;

use Src\Entities\Student;
use Src\Entities\User;
use Src\Repository\{UserRepository, StudentRepository};

class StudentController
{

    public function __construct(protected StudentRepository $studentRepository,protected UserRepository $userRepository)
    {
    }

    public function create($data)
    {
        $data['role'] = 'STD';
        $user = new User($data);
        $userId = $this->userRepository->create($user->toArray());
        $data['user_id'] = $userId;
        $student = new Student($data);
        $this->studentRepository->create($student->toArray());
        return array_merge($user->toArray(), $student->toArray());
    }

    public function update($id, $data)
    {
        $student = new Student($data);
        $this->studentRepository->update($id, $student->toArray());
        $user = new User($data);
        $this->userRepository->updateByStudent($id, $user->toArray());
        return array_merge($user->toArray(true), $student->toArray());
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