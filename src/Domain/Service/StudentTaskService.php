<?php

namespace App\Domain\Service;

use App\Domain\Bus\AddStudentStatsBusInterface;
use App\Domain\DTO\ReviewedTaskResultsMessage;
use App\Domain\DTO\ReviewStudentTaskDTO;
use App\Domain\Entity\Skill;
use App\Domain\Entity\StudentStats;
use App\Domain\Entity\StudentTask;
use App\Domain\Enum\TaskStatus;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\UpdateStudentTaskModel;
use App\Infrastructure\Repository\StudentTaskRepository;

class StudentTaskService
{
    public function __construct(
        private readonly StudentTaskRepository $studentTaskRepository,
        private readonly StudentStatsService $studentStatsService,
        private readonly SkillService $skillService,
        private readonly AddStudentStatsBusInterface $addStudentStatsBus,
    ) {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function reviewStudentTask(ReviewStudentTaskDTO $reviewStudentTaskDTO, int $studentTaskId): void
    {
        if (empty($reviewStudentTaskDTO->skillIds)) {
            $this->updateStudentTaskStatus($studentTaskId, TaskStatus::ForRevision);
            return;
        }

        $studentTask = $this->studentTaskRepository->findById($studentTaskId);
        if ($studentTask === null) {
            throw new EntityNotFoundException(StudentTask::class);
        }

        $requiredSkillIds = array_map(
            static fn(Skill $skill) => $skill->getId(),
            $this->skillService->getSkillsByTaskId($studentTask->getTask()->getId(), requiredSkillsOnly: true),
        );

        $acceptedSkillIds = array_map(
            static fn(StudentStats $stats) => $stats->getSkill()->getId(),
            $this->studentStatsService->getStudentStatsRaw(
                student: $studentTask->getStudent()->getId(),
                task: $studentTask->getTask()->getId()
            ),
        );
        $acceptedSkillIds = array_merge($acceptedSkillIds, $reviewStudentTaskDTO->skillIds);

        $this->addStudentStatsBus->sendReviewedTaskResultsMessageAsync(
            new ReviewedTaskResultsMessage(
                studentId: $studentTask->getStudent()->getId(),
                skillIds: $reviewStudentTaskDTO->skillIds,
                studentTaskId: $studentTaskId,
                taskStatus: $reviewStudentTaskDTO->taskStatus->value,
                taskMinRequirements: $requiredSkillIds === array_intersect($requiredSkillIds, $acceptedSkillIds),
            )
        );
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateStudentTaskStatus(int $studentTaskId, TaskStatus $taskStatus): void
    {
        $studentTask = $this->studentTaskRepository->findById($studentTaskId);

        if ($studentTask === null) {
            throw new EntityNotFoundException(StudentTask::class);
        }

        $studentTask->setTaskStatus($taskStatus);
        $this->studentTaskRepository->update();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateStudentTask(UpdateStudentTaskModel $updateStudentTaskModel): void
    {
        $studentTask = $this->studentTaskRepository->findById($updateStudentTaskModel->studentTaskId);

        if ($studentTask === null) {
            throw new EntityNotFoundException(StudentTask::class);
        }

        $studentTask->setTaskStatus($updateStudentTaskModel->taskStatus);
        $studentTask->setTaskMinRequirements($updateStudentTaskModel->taskMinRequirements);
        $this->studentTaskRepository->update();
    }
}
