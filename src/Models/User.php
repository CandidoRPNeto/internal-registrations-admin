<?php
namespace Src\Models;

class User implements Model
{
    
    public function getTableName(): string{
        return "users";
    }

    public function getFields(): array{
        return [

        ];
    }
    
    public function getNameField(): string{
        return "name";
    }
}