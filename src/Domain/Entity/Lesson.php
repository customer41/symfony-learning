<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\EntityInterface;
use App\Domain\Entity\Interfaces\HasMetaTimestampsInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Index(name: 'lesson__module_id__ind', columns: ['module_id'])]
class Lesson implements EntityInterface, HasMetaTimestampsInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $target;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $description;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $results;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $teacher;

    #[ORM\Column(name: 'date_time', type: 'datetime', nullable: true)]
    private ?\DateTime $dateTime;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $duration;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'lesson')]
    private Collection $tasks;

    #[ORM\ManyToOne(targetEntity: Module::class, inversedBy: 'lessons')]
    #[ORM\JoinColumn(name: 'module_id', referencedColumnName: 'id')]
    private ?Module $module;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getResults(): string
    {
        return $this->results;
    }

    public function setResults(string $results): void
    {
        $this->results = $results;
    }

    public function getTeacher(): ?string
    {
        return $this->teacher;
    }

    public function setTeacher(?string $teacher): void
    {
        $this->teacher = $teacher;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(?\DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): void
    {
        $this->duration = $duration;
    }

    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): void
    {
        $this->module = $module;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
