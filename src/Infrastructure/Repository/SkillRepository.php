<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Skill;
use Doctrine\ORM\EntityManagerInterface;

class SkillRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, Skill::class);
    }

    public function create(Skill $skill): int
    {
        return $this->store($skill);
    }

    public function findById(int $id): ?Skill
    {
        return $this->repositoryApi->find($id);
    }
}
