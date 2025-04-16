<?php
namespace Src\Controllers;

use Src\Repository\EnrollmentsRepository;

class EnrollmentsController
{
    protected $repository;
    public function __construct()
    {
        $this->repository = new EnrollmentsRepository();
    }

    public function create($data)
    {
        $obj = $this->repository->create($data);
        return [
            'code' => 200,
            'data' => $obj
        ];
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function search($page, $search, $filter = 1)
    {
        if ($filter == 1) {
            $search = ['username' => $search];
        } else if ($filter == 2) {
            $search = ['classname' => $search];
        }
        return $this->repository->listAll($page, $search);
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