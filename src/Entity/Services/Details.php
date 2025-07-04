<?php

namespace App\Entity\Services;

use App\Entity\Scheduling\Appointment;
use App\Entity\Workers\WorkerRole;
use App\Repository\Services\DetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailsRepository::class)]
class Details
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Services>
     */
    #[ORM\OneToMany(targetEntity: Services::class, mappedBy: 'details')]
    private Collection $ServicesId;

    /**
     * @var Collection<int, WorkerRole>
     */
    #[ORM\OneToMany(targetEntity: WorkerRole::class, mappedBy: 'details')]
    private Collection $workerRoleId;

    #[ORM\Column]
    private ?int $slots = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $changedAt = null;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'detailsId')]
    private Collection $appointments;

    public function __construct()
    {
        $this->ServicesId = new ArrayCollection();
        $this->workerRoleId = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Services>
     */
    public function getServicesId(): Collection
    {
        return $this->ServicesId;
    }

    public function addServicesId(Services $servicesId): static
    {
        if (!$this->ServicesId->contains($servicesId)) {
            $this->ServicesId->add($servicesId);
            $servicesId->setDetails($this);
        }

        return $this;
    }

    public function removeServicesId(Services $servicesId): static
    {
        if ($this->ServicesId->removeElement($servicesId)) {
            // set the owning side to null (unless already changed)
            if ($servicesId->getDetails() === $this) {
                $servicesId->setDetails(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorkerRole>
     */
    public function getWorkerRoleId(): Collection
    {
        return $this->workerRoleId;
    }

    public function addWorkerRoleId(WorkerRole $workerRoleId): static
    {
        if (!$this->workerRoleId->contains($workerRoleId)) {
            $this->workerRoleId->add($workerRoleId);
            $workerRoleId->setDetails($this);
        }

        return $this;
    }

    public function removeWorkerRoleId(WorkerRole $workerRoleId): static
    {
        if ($this->workerRoleId->removeElement($workerRoleId)) {
            // set the owning side to null (unless already changed)
            if ($workerRoleId->getDetails() === $this) {
                $workerRoleId->setDetails(null);
            }
        }

        return $this;
    }

    public function getSlots(): ?int
    {
        return $this->slots;
    }

    public function setSlots(int $slots): static
    {
        $this->slots = $slots;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

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

    public function getChangedAt(): ?\DateTimeImmutable
    {
        return $this->changedAt;
    }

    public function setChangedAt(\DateTimeImmutable $changedAt): static
    {
        $this->changedAt = $changedAt;

        return $this;
    }

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setDetailsId($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getDetailsId() === $this) {
                $appointment->setDetailsId(null);
            }
        }

        return $this;
    }
}
