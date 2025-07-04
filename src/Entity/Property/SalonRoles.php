<?php

namespace App\Entity\Property;

use App\Entity\User\User;
use App\Entity\Workers\WorkerRole;
use App\Repository\Property\SalonRolesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonRolesRepository::class)]
class SalonRoles
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'salonRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $UserId = null;

    #[ORM\ManyToOne(inversedBy: 'salonRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salon $salonId = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $alteredAt = null;

    #[ORM\OneToOne(mappedBy: 'salonRolesId', cascade: ['persist', 'remove'])]
    private ?WorkerRole $workerRole = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): static
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getSalonId(): ?Salon
    {
        return $this->salonId;
    }

    public function setSalonId(?Salon $salonId): static
    {
        $this->salonId = $salonId;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAlteredAt(): ?\DateTimeImmutable
    {
        return $this->alteredAt;
    }

    public function setAlteredAt(?\DateTimeImmutable $alteredAt): static
    {
        $this->alteredAt = $alteredAt;

        return $this;
    }

    public function getWorkerRole(): ?WorkerRole
    {
        return $this->workerRole;
    }

    public function setWorkerRole(WorkerRole $workerRole): static
    {
        // set the owning side of the relation if necessary
        if ($workerRole->getSalonRolesId() !== $this) {
            $workerRole->setSalonRolesId($this);
        }

        $this->workerRole = $workerRole;

        return $this;
    }
}
