<?php

namespace App\Domain\Service;

use App\Domain\Entity\Student;
use App\Domain\Entity\StudentStats;
use App\Domain\Exception\EntityNotFoundException;
use App\Domain\Model\CreateStudentStatsModel;
use App\Infrastructure\Repository\StudentStatsRepository;

class StudentStatsService
{
    public function __construct(
        private readonly StudentStatsRepository $studentStatsRepository,
        private readonly SkillService $skillService,
        private readonly StudentService $studentService,
    ) {
    }

    /**
     * @note: Each parameter is identifier(s) of related entities with stats
     *
     * @return StudentStats[]
     */
    public function getStudentStatsRaw(
        int|array|null $student,
        int|array|null $course = null,
        int|array|null $module = null,
        int|array|null $lesson = null,
        int|array|null $task = null,
        int|array|null $skill = null,
    ): array {
        return $this->studentStatsRepository->findByIds($student, $course, $module, $lesson, $task, $skill);
    }

    /**
     * @param CreateStudentStatsModel $createStatsModel
     *
     * @throws EntityNotFoundException
     */
    public function addStudentStats(CreateStudentStatsModel $createStatsModel): void
    {
        $student = $this->studentService->findStudentById($createStatsModel->studentId);
        if ($student === null) {
            throw new EntityNotFoundException(Student::class);
        }

        $studentStats = [];
        $skills = $this->skillService->findSkillByIds($createStatsModel->skillIds);
        foreach ($skills as $skill) {
            $studentStats[] = new StudentStats(
                $student,
                $skill->getTask()->getLesson()->getModule()->getCourse(),
                $skill->getTask()->getLesson()->getModule(),
                $skill->getTask()->getLesson(),
                $skill->getTask(),
                $skill,
                $createStatsModel->getScoreBySkill($skill),
            );
        }

        $this->studentStatsRepository->createBatch($studentStats);
    }
}
