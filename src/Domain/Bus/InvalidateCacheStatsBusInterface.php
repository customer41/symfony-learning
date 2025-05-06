<?php

namespace App\Domain\Bus;

use App\Domain\DTO\AddedStatsMessage;

interface InvalidateCacheStatsBusInterface
{
    public function sendAddedStatsMessageAsync(AddedStatsMessage $addedStatsMessage): bool;
}
