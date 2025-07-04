<?php

namespace App\Entity\Services;

use App\Entity\Property\Salon;
use App\Repository\Services\ServicesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salon $SalonId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $alteredAt = null;

    #[ORM\ManyToOne(inversedBy: 'ServicesId')]
    private ?Details $details = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalonId(): ?Salon
    {
        return $this->SalonId;
    }

    public function setSalonId(?Salon $SalonId): static
    {
        $this->SalonId = $SalonId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function setAlteredAt(\DateTimeImmutable $alteredAt): static
    {
        $this->alteredAt = $alteredAt;

        return $this;
    }

    public function getDetails(): ?Details
    {
        return $this->details;
    }

    public function setDetails(?Details $details): static
    {
        $this->details = $details;

        return $this;
    }
}
