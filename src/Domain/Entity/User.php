<?php

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\EntityInterface;
use App\Domain\Entity\Interfaces\HasMetaTimestampsInterface;
use App\Domain\Enum\Role;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
class User implements EntityInterface, HasMetaTimestampsInterface, UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $firstName;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $lastName;

    #[ORM\Column(type: 'string', nullable: false)]
    private string $password;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $isActive;

    #[ORM\Column(type: 'json', length: 1024, nullable: false)]
    private array $roles = [];

    #[ORM\Column(type: 'string', length: 32, unique: true, nullable: true)]
    private ?string $apiToken = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Manager $manager = null;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Student $student = null;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private \DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private \DateTime $updatedAt;

    #[ORM\Column(name: 'deleted_at', type: 'datetime', nullable: true)]
    private ?\DateTime $deletedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = Role::User->value;

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): void
    {
        $this->apiToken = $apiToken;
    }

    public function getManager(): ?Manager
    {
        return $this->manager;
    }

    public function setManager(?Manager $manager): void
    {
        if ($manager->getUser() !== $this) {
            $manager->setUser($this);
        }

        $this->manager = $manager;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): void
    {
        if ($student->getUser() !== $this) {
            $student->setUser($this);
        }

        $this->student = $student;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
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
