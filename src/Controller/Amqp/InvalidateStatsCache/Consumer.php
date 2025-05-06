<?php

namespace App\Controller\Amqp\InvalidateStatsCache;

use App\Application\RabbitMq\AbstractConsumer;
use App\Controller\Amqp\InvalidateStatsCache\Input\Message;
use App\Controller\DTO\AmqpMessageInterface;
use App\Infrastructure\DTO\StudentCourseDTO;
use App\Infrastructure\Service\StudentStatsCacheService;
use Psr\Cache\InvalidArgumentException;

class Consumer extends AbstractConsumer
{
    public function __construct(
        private readonly StudentStatsCacheService $studentStatsCacheService,
    ) {
    }

    /**
     * @param Message $message
     */
    protected function handle(AmqpMessageInterface $message): int
    {
        try {
            $this->studentStatsCacheService->invalidateCache(
                new StudentCourseDTO($message->studentId, $message->courseId, null)
            );
        } catch (InvalidArgumentException $e) {
            $this->reject($e->getMessage());
        }

        return self::MSG_ACK;
    }

    protected function getMessageClass(): string
    {
        return Message::class;
    }
}
