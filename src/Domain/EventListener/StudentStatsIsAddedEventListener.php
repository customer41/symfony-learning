<?php

namespace App\Domain\EventListener;

use App\Domain\Event\StudentStatsIsAddedEvent;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Service\StudentTaskService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: StudentStatsIsAddedEvent::class, method: 'onStudentStatsIsAdded')]
readonly class StudentStatsIsAddedEventListener
{
    public function __construct(
        private StudentTaskService $studentTaskService,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function onStudentStatsIsAdded(StudentStatsIsAddedEvent $event): void
    {
        $this->studentTaskService->updateStudentTask($event->getUpdateStudentTaskModel());
    }
}
