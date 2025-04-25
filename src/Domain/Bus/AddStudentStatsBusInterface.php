<?php

namespace App\Domain\Bus;

use App\Domain\DTO\ReviewedTaskResultsMessage;

interface AddStudentStatsBusInterface
{
    public function sendReviewedTaskResultsMessageAsync(ReviewedTaskResultsMessage $reviewedTaskResultsMessage): bool;
}
