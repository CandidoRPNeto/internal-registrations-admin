<?php
namespace Src\Controllers;

use Src\Models\Classroom;

class ClassroomController extends CrudController
{
    public function __construct() {
        parent::__construct(new Classroom());
    }
}