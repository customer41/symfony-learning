<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\EntityInterface;
use App\Domain\Enum\TaskStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\UniqueConstraint(columns: ['student_id', 'task_id'])]
class StudentTask implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'student_id', referencedColumnName: 'id')]
    private readonly Student $student;

    #[ORM\ManyToOne(targetEntity: Task::class, inversedBy: 'students')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id')]
    private readonly Task $task;

    #[ORM\Column(name: 'task_status', type: 'string', enumType: TaskStatus::class)]
    private TaskStatus $taskStatus;

    #[ORM\Column(name: 'task_min_requirements', type: 'boolean')]
    private bool $taskMinRequirements;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private readonly \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private \DateTime $updatedAt;

    public function __construct(
        Student $student,
        Task $task,
        TaskStatus $taskStatus = TaskStatus::New,
        bool $taskMinRequirements = false,
    ) {
        $this->student = $student;
        $this->task = $task;
        $this->taskStatus = $taskStatus;
        $this->taskMinRequirements = $taskMinRequirements;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getTaskStatus(): TaskStatus
    {
        return $this->taskStatus;
    }

    public function setTaskStatus(TaskStatus $taskStatus): void
    {
        $this->taskStatus = $taskStatus;
    }

    public function isTaskMinRequirements(): bool
    {
        return $this->taskMinRequirements;
    }

    public function setTaskMinRequirements(bool $taskMinRequirements): void
    {
        $this->taskMinRequirements = $taskMinRequirements;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
