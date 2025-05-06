<?php

namespace App\Infrastructure\Bus\Adapter;

use App\Domain\Bus\InvalidateCacheStatsBusInterface;
use App\Domain\DTO\AddedStatsMessage;
use App\Infrastructure\Bus\AmqpExchangeEnum;
use App\Infrastructure\Bus\RabbitMqBus;

class InvalidateCacheStatsRabbitMqBus implements InvalidateCacheStatsBusInterface
{
    public function __construct(private readonly RabbitMqBus $rabbitMqBus) {}

    public function sendAddedStatsMessageAsync(AddedStatsMessage $addedStatsMessage): bool
    {
        return $this->rabbitMqBus->publishToExchange(AmqpExchangeEnum::InvalidateStatsCache, $addedStatsMessage);
    }
}
