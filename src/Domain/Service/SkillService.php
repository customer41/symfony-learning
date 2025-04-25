<?php

namespace App\Domain\Service;

use App\Domain\Entity\Skill;
use App\Infrastructure\Repository\SkillRepository;

class SkillService
{
    public function __construct(
        private readonly SkillRepository $skillRepository,
    ) {
    }

    /**
     * @param int[] $ids
     *
     * @return Skill[]
     */
    public function findSkillByIds(array $ids): array
    {
        return $this->skillRepository->findByIds($ids);
    }

    /**
     * @return Skill[]
     */
    public function getSkillsByTaskId(int $taskId, bool $requiredSkillsOnly = false): array
    {
        return $this->skillRepository->findByTaskId($taskId, $requiredSkillsOnly);
    }
}
