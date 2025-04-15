<?php
namespace Src\Models;

class Student implements Model
{
    
    public function getTableName(): string{
        return "students";
    }

    public function getFields(): array{
        return [

        ];
    }
    
    public function getNameField(): string{
        return "name";
    }
}