<?php
namespace Src\Models;

interface Model
{
    function getTableName(): string;

    function getFields(): array;

    function getNameField(): string;

}