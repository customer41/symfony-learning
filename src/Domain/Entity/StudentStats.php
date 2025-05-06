<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\EntityInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\UniqueConstraint(columns: ['student_id', 'skill_id'])]
class StudentStats implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class)]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id', nullable: false)]
    private readonly Student $student;

    #[ORM\ManyToOne(targetEntity: Course::class)]
    #[ORM\JoinColumn(name: 'course_id', referencedColumnName: 'id', nullable: false)]
    private readonly Course $course;

    #[ORM\ManyToOne(targetEntity: Module::class)]
    #[ORM\JoinColumn(name: 'module_id', referencedColumnName: 'id', nullable: false)]
    private readonly Module $module;

    #[ORM\ManyToOne(targetEntity: Lesson::class)]
    #[ORM\JoinColumn(name: 'lesson_id', referencedColumnName: 'id', nullable: false)]
    private readonly Lesson $lesson;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', nullable: false)]
    private readonly Task $task;

    #[ORM\ManyToOne(targetEntity: Skill::class)]
    #[ORM\JoinColumn(name: 'skill_id', referencedColumnName: 'id', nullable: false)]
    private readonly Skill $skill;

    #[ORM\Column(type: 'integer')]
    private readonly int $score;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private readonly \DateTime $createdAt;

    public function __construct(
        Student $student,
        Course $course,
        Module $module,
        Lesson $lesson,
        Task $task,
        Skill $skill,
        int $score,
    ) {
        $this->student = $student;
        $this->course = $course;
        $this->module = $module;
        $this->lesson = $lesson;
        $this->task = $task;
        $this->skill = $skill;
        $this->score = $score;
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getCourse(): Course
    {
        return $this->course;
    }

    public function getModule(): Module
    {
        return $this->module;
    }

    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getSkill(): Skill
    {
        return $this->skill;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
