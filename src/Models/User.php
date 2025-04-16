<?php
namespace Src\Models;

class User implements Model
{

    public function getTableName(): string
    {
        return "users";
    }

    public function getFields(): array
    {
        return [
            'name',
            'email',
            'password',
            'role'
        ];
    }

    public function getOrdenation(): string
    {
        return "name";
    }
}