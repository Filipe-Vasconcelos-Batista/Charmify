<?php

namespace App\Entity\Scheduling;

use App\Entity\Workers\WorkerRole;
use App\Repository\Scheduling\CalendarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'calendars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?WorkerRole $workerRoleId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column]
    private ?int $slotNumber = null;

    /**
     * @var Collection<int, Appointment>
     */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'calendarId')]
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkerRoleId(): ?WorkerRole
    {
        return $this->workerRoleId;
    }

    public function setWorkerRoleId(?WorkerRole $workerRoleId): static
    {
        $this->workerRoleId = $workerRoleId;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getSlotNumber(): ?int
    {
        return $this->slotNumber;
    }

    public function setSlotNumber(int $slotNumber): static
    {
        $this->slotNumber = $slotNumber;

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
            $appointment->setCalendarId($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getCalendarId() === $this) {
                $appointment->setCalendarId(null);
            }
        }

        return $this;
    }
}
