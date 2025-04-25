<?php

namespace App\Controller\Web\ReviewStudentTask\v1\Input;

use App\Domain\Enum\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ReviewStudentTaskDTO
{
    /**
     * @param int[] $skillIds
     */
    public function __construct(
        #[Assert\Choice(callback: [TaskStatus::class, 'values'])]
        public string $taskStatus,
        #[Assert\Unique]
        #[Assert\All([new Assert\Type(type: 'integer')])]
        public array $skillIds = [],
    ) {
    }
}
