<?php

namespace App\Entity\Workers;

use App\Repository\Workers\TimeOffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeOffRepository::class)]
class TimeOff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, WorkerRole>
     */
    #[ORM\OneToMany(targetEntity: WorkerRole::class, mappedBy: 'timeOff')]
    private Collection $workerRoleId;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $end = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reason = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $changedAt = null;

    public function __construct()
    {
        $this->workerRoleId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $workerRoleId->setTimeOff($this);
        }

        return $this;
    }

    public function removeWorkerRoleId(WorkerRole $workerRoleId): static
    {
        if ($this->workerRoleId->removeElement($workerRoleId)) {
            // set the owning side to null (unless already changed)
            if ($workerRoleId->getTimeOff() === $this) {
                $workerRoleId->setTimeOff(null);
            }
        }

        return $this;
    }

    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    public function setStart(\DateTime $start): static
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end): static
    {
        $this->end = $end;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): static
    {
        $this->reason = $reason;

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
}
