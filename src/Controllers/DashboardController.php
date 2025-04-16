<?php
namespace Src\Controllers;

use Src\Models\Classroom;
use Src\Models\Enrollments;
use Src\Models\User;
use Src\Repository\CrudRepository;

session_start();
class DashboardController
{
    public function getInfo(): array
    {
        $classroomRepository = new CrudRepository(new Classroom());
        $enrollmentsRepository = new CrudRepository(new Enrollments());
        $userRepository = new CrudRepository(new User());
        return [
            'code' => 200,
            'data' => [
                'students' => $userRepository->count(['role' => 'STD']), 
                'class' => $classroomRepository->count(), 
                'enrollments' => $enrollmentsRepository->count()
            ]
        ];
    }
}