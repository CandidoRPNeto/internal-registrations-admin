<?php
namespace Src\Controllers;

use Src\Entities\Classroom;
use Src\Repository\ClassroomRepository;

class ClassroomController
{
    protected ClassroomRepository $repository;
    public function __construct()
    {
        $this->repository = new ClassroomRepository();
    }

    public function create($data)
    {
        $class = new Classroom($data);
        $this->repository->create($class->toArray());
        return $class->toArray();
    }

    public function update($id, $data)
    {
        $class = new Classroom($data);
        $this->repository->update($id, $class->toArray());
        return $class->toArray();
    }

    public function search($page, $search, $filter = 1)
    {
       //TODO
    }

    public function show($id)
    {
        return $this->repository->read($id);
    }

    public function index($page)
    {
        return $this->repository->listAll($page);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}