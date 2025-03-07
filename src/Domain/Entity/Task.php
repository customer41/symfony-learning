<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\EntityInterface;
use App\Domain\Entity\Interfaces\HasMetaTimestampsInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Index(name: 'task__lesson_id__ind', columns: ['lesson_id'])]
class Task implements EntityInterface, HasMetaTimestampsInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $target;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $description;

    #[ORM\Column(type: 'text', nullable: false)]
    private string $criteria;

    #[ORM\ManyToOne(targetEntity: Lesson::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'lesson_id', referencedColumnName: 'id')]
    private Lesson $lesson;

    #[ORM\OneToMany(targetEntity: Skill::class, mappedBy: 'task')]
    private Collection $skills;

    #[ORM\Column(name: 'submit_before', type: 'datetime', nullable: true)]
    private \DateTime $submitBefore;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private \DateTime $updatedAt;

    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private ?\DateTime $deletedAt = null;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
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

    public function getCriteria(): string
    {
        return $this->criteria;
    }

    public function setCriteria(string $criteria): void
    {
        $this->criteria = $criteria;
    }

    public function getLesson(): Lesson
    {
        return $this->lesson;
    }

    public function setLesson(Lesson $lesson): void
    {
        $this->lesson = $lesson;
    }

    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function getSubmitBefore(): ?\DateTime
    {
        return $this->submitBefore;
    }

    public function setSubmitBefore(?\DateTime $submitBefore): void
    {
        $this->submitBefore = $submitBefore;
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

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(): void
    {
        $this->deletedAt = new \DateTime();
    }
}
