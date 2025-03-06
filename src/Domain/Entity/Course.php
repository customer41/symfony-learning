<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\EntityInterface;
use App\Domain\Entity\Interfaces\HasMetaTimestampsInterface;
use App\Domain\Enum\CourseStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Index(name: 'course__status__start_date__ind', columns: ['status', 'start_date'], options: ['where' => '(start_date IS NOT NULL)'])]
class Course implements EntityInterface, HasMetaTimestampsInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(type: 'string', nullable: false, enumType: CourseStatus::class)]
    private CourseStatus $status;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $manager;

    #[ORM\OneToMany(targetEntity: Module::class, mappedBy: 'course')]
    private Collection $modules;

    #[ORM\Column(name: 'start_date', type: 'datetime', nullable: true)]
    private ?\DateTime $startDate;

    #[ORM\Column(name: 'end_date', type: 'datetime', nullable: true)]
    private ?\DateTime $endDate;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
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

    public function getStatus(): CourseStatus
    {
        return $this->status;
    }

    public function getManager(): ?string
    {
        return $this->manager;
    }

    public function setManager(string $manager): void
    {
        $this->manager = $manager;
    }

    public function setStatus(CourseStatus $status): void
    {
        $this->status = $status;
    }

    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
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
