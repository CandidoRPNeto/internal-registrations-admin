<?php
namespace Src\Models;

class Student implements Model
{

    public function getTableName(): string
    {
        return "students";
    }

    public function getFields(): array
    {
        return [
            'birth_date',
            'cpf',
            'user_id'
        ];
    }

    public function getOrdenation(): string
    {
        return "birth_date DESC";
    }
}