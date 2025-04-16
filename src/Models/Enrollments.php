<?php
namespace Src\Models;

class Enrollments implements Model
{
    
    public function getTableName(): string{
        return "enrollments";
    }

    public function getFields(): array{
        return [

        ];
    }
    
    public function getOrdenation(): string{
        return "created_at DESC";
    }
}