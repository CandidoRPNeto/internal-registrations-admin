<?php
namespace Src\Models;

class Classroom implements Model
{
    
    public function getTableName(): string{
        return "classrooms";
    }

    public function getFields(): array{
        return [
            'id',
            'name',
            'description'
        ];
    }
    
    public function getNameField(): string{
        return "name";
    }
}