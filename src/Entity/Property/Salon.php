<?php

namespace App\Entity\Property;

use App\Entity\Services\Services;
use App\Repository\Property\SalonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalonRepository::class)]
class Salon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    /**
     * @var Collection<int, SalonRoles>
     */
    #[ORM\OneToMany(targetEntity: SalonRoles::class, mappedBy: 'salonId')]
    private Collection $salonRoles;

    /**
     * @var Collection<int, Services>
     */
    #[ORM\OneToMany(targetEntity: Services::class, mappedBy: 'SalonId')]
    private Collection $services;

    public function __construct()
    {
        $this->salonRoles = new ArrayCollection();
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection<int, SalonRoles>
     */
    public function getSalonRoles(): Collection
    {
        return $this->salonRoles;
    }

    public function addSalonRole(SalonRoles $salonRole): static
    {
        if (!$this->salonRoles->contains($salonRole)) {
            $this->salonRoles->add($salonRole);
            $salonRole->setSalonId($this);
        }

        return $this;
    }

    public function removeSalonRole(SalonRoles $salonRole): static
    {
        if ($this->salonRoles->removeElement($salonRole)) {
            // set the owning side to null (unless already changed)
            if ($salonRole->getSalonId() === $this) {
                $salonRole->setSalonId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Services>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Services $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setSalonId($this);
        }

        return $this;
    }

    public function removeService(Services $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getSalonId() === $this) {
                $service->setSalonId(null);
            }
        }

        return $this;
    }
}
