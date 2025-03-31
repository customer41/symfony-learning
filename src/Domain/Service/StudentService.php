<?php

namespace App\Domain\Service;

use App\Domain\Entity\Student;
use App\Domain\Model\CreateStudentModel;
use App\Infrastructure\Repository\StudentRepository;

class StudentService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly StudentRepository $studentRepository,
    ) {
    }

    public function createStudent(CreateStudentModel $createStudentModel): Student
    {
        $user = $this->userService->findUserById($createStudentModel->userId);

        $student = new Student();
        $student->setUser($user);
        $student->setBirthDate($createStudentModel->birthDate);
        $student->setAge($createStudentModel->age);
        $student->setGender($createStudentModel->gender);
        $student->setPhone($createStudentModel->phone);
        $this->studentRepository->create($student);

        return $student;
    }
}
