<?php

namespace App\Entity\Scheduling;

use App\Entity\Services\Details;
use App\Enum\AppointmentStatusEnum;
use App\Repository\Scheduling\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(enumType: AppointmentStatusEnum::class)]
    private ?AppointmentStatusEnum $status = null;

    #[ORM\Column(length: 10000, nullable: true)]
    private ?string $notes = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Details $detailsId = null;

    #[ORM\ManyToOne(inversedBy: 'appointments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Calendar $calendarId = null;

    #[ORM\Column(length: 255)]
    private ?string $User = null;

    #[ORM\Column(nullable: true)]
    private ?int $rating = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $review = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $priceAtBooking = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?AppointmentStatusEnum
    {
        return $this->status;
    }

    public function setStatus(AppointmentStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }

    public function getDetailsId(): ?Details
    {
        return $this->detailsId;
    }

    public function setDetailsId(?Details $detailsId): static
    {
        $this->detailsId = $detailsId;

        return $this;
    }

    public function getCalendarId(): ?Calendar
    {
        return $this->calendarId;
    }

    public function setCalendarId(?Calendar $calendarId): static
    {
        $this->calendarId = $calendarId;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->User;
    }

    public function setUser(string $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): static
    {
        $this->review = $review;

        return $this;
    }

    public function getPriceAtBooking(): ?string
    {
        return $this->priceAtBooking;
    }

    public function setPriceAtBooking(string $priceAtBooking): static
    {
        $this->priceAtBooking = $priceAtBooking;

        return $this;
    }
}
