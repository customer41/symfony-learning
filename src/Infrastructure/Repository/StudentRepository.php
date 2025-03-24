<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;

class StudentRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Student::class);
    }

    public function create(Student $student): int
    {
        return $this->store($student);
    }

    public function findById(int $id): ?Student
    {
        return $this->repositoryApi->find($id);
    }
}
