<?php

namespace App\Domain\Event;

use App\Domain\Model\UpdateStudentTaskModel;

readonly class StudentStatsIsAddedEvent
{
    public function __construct(
        private UpdateStudentTaskModel $updateStudentTaskModel,
    ) {
    }

    public function getUpdateStudentTaskModel(): UpdateStudentTaskModel
    {
        return $this->updateStudentTaskModel;
    }
}
