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

    /**
     * @param int[] $ids
     *
     * @return Skill[]
     */
    public function findByIds(array $ids): array
    {
        return $this->repositoryApi->findBy(['id' => $ids]);
    }

    /**
     * @return Skill[]
     */
    public function findByTaskId(int $taskId, bool $requiredSkillsOnly = false): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('s')
            ->from(Skill::class, 's')
            ->join('s.task', 't')
            ->where('t.id = :taskId');

        if ($requiredSkillsOnly) {
            $queryBuilder->andWhere('s.isRequired = true');
        }

        return $queryBuilder
            ->setParameter('taskId', $taskId)
            ->getQuery()
            ->getResult();
    }
}
