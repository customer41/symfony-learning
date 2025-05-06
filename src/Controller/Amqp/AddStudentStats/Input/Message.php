<?php

namespace App\Controller\Amqp\AddStudentStats\Input;

use App\Controller\DTO\AmqpMessageInterface;
use App\Domain\Enum\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

readonly class Message implements AmqpMessageInterface
{
    /**
     * @param int[] $skillIds
     */
    public function __construct(
        public int $studentId,
        #[Assert\Unique]
        #[Assert\All([new Assert\Type(type: 'integer')])]
        public array $skillIds,
        public int $studentTaskId,
        #[Assert\Choice(callback: [TaskStatus::class, 'values'])]
        public string $taskStatus,
        public bool $taskMinRequirements,
    ) {
    }
}
