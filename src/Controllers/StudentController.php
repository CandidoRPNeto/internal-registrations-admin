<?php
namespace Src\Controllers;

use Src\Models\Student;

class StudentController extends CrudController
{
    public function __construct() {
        parent::__construct(new Student());
    }
}