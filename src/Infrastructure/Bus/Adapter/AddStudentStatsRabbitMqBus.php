<?php

namespace App\Infrastructure\Bus\Adapter;

use App\Domain\Bus\AddStudentStatsBusInterface;
use App\Domain\DTO\ReviewedTaskResultsMessage;
use App\Infrastructure\Bus\AmqpExchangeEnum;
use App\Infrastructure\Bus\RabbitMqBus;

class AddStudentStatsRabbitMqBus implements AddStudentStatsBusInterface
{
    public function __construct(private readonly RabbitMqBus $rabbitMqBus) {}

    public function sendReviewedTaskResultsMessageAsync(ReviewedTaskResultsMessage $reviewedTaskResultsMessage): bool
    {
        return $this->rabbitMqBus->publishToExchange(AmqpExchangeEnum::AddStudentStats, $reviewedTaskResultsMessage);
    }
}
