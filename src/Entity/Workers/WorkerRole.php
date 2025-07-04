<?php

namespace App\Entity\Workers;

use App\Entity\Property\SalonRoles;
use App\Enum\WeekDaysEnum;
use App\Repository\Workers\WorkerRoleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerRoleRepository::class)]
class WorkerRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'workerRole', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?SalonRoles $salonRolesId = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: WeekDaysEnum::class)]
    private array $workDays = [];

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $startWorkTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $endWorkTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $startBreakTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $endBreakTime = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $changedAt = null;

    #[ORM\ManyToOne(inversedBy: 'workerRoleId')]
    private ?TimeOff $timeOff = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSalonRolesId(): ?SalonRoles
    {
        return $this->salonRolesId;
    }

    public function setSalonRolesId(SalonRoles $salonRolesId): static
    {
        $this->salonRolesId = $salonRolesId;

        return $this;
    }

    /**
     * @return WeekDaysEnum[]
     */
    public function getWorkDays(): array
    {
        return $this->workDays;
    }

    public function setWorkDays(array $workDays): static
    {
        $this->workDays = $workDays;

        return $this;
    }

    public function getStartWorkTime(): ?\DateTime
    {
        return $this->startWorkTime;
    }

    public function setStartWorkTime(\DateTime $startWorkTime): static
    {
        $this->startWorkTime = $startWorkTime;

        return $this;
    }

    public function getEndWorkTime(): ?\DateTime
    {
        return $this->endWorkTime;
    }

    public function setEndWorkTime(\DateTime $endWorkTime): static
    {
        $this->endWorkTime = $endWorkTime;

        return $this;
    }

    public function getStartBreakTime(): ?\DateTime
    {
        return $this->startBreakTime;
    }

    public function setStartBreakTime(?\DateTime $startBreakTime): static
    {
        $this->startBreakTime = $startBreakTime;

        return $this;
    }

    public function getEndBreakTime(): ?\DateTime
    {
        return $this->endBreakTime;
    }

    public function setEndBreakTime(?\DateTime $endBreakTime): static
    {
        $this->endBreakTime = $endBreakTime;

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

    public function getTimeOff(): ?TimeOff
    {
        return $this->timeOff;
    }

    public function setTimeOff(?TimeOff $timeOff): static
    {
        $this->timeOff = $timeOff;

        return $this;
    }
}
