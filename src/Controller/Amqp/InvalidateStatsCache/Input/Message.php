<?php

namespace App\Controller\Amqp\InvalidateStatsCache\Input;

use App\Controller\DTO\AmqpMessageInterface;

readonly class Message implements AmqpMessageInterface
{
    public function __construct(
        public int $studentId,
        public int $courseId,
    ) {
    }
}
