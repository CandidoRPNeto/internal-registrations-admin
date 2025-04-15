<?php
namespace Src\Controllers;

use Src\Models\Model;
use Src\Repository\CrudRepository;

class CrudController
{
    protected $repository;
    public function __construct(Model $model)
    {
        $this->repository = new CrudRepository($model);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id, $data);
    }

    public function show($id)
    {
        return $this->repository->read($id);
    }

    public function index()
    {
        return $this->repository->listAll();
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
}