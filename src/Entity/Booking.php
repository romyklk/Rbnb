<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $booker = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ad $ad = null;

    #[ORM\Column]

    #[Assert\Type("\DateTimeInterface", message: 'La date d\'arrivée doit être au bon format')]
    #[Assert\GreaterThan('today', message: 'La date d\'arrivée doit être ultérieure à la date d\'aujourd\'hui')]
    #[Assert\NotNull(message: 'La date d\'arrivée doit être renseignée')]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    #[Assert\Type("\DateTimeInterface", message: 'La date de départ doit être au bon format')]
    #[Assert\GreaterThan(propertyPath: 'startDate', message: 'La date de départ doit être ultérieure à la date d\'arrivée')]
    #[Assert\NotNull(message: 'La date de départ doit être renseignée')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $reservationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function prePersist(): void
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function getDuration(): int
    {
        $diff = $this->startDate->diff($this->createdAt);

        return $diff->days;
    }

    public function isbookableDates(): bool
    {
        // 1) il faut connaitre les dates qui sont impossibles pour l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();

        // 2) il faut comparer les dates choisies avec les dates impossibles
        $bookingDays = $this->getDays();

        // 3) il faut comparer les deux tableaux
        $formatDay = function ($day) {
            return $day->format('Y-m-d');
        };

        // Tableau des chaines de caractères de mes journées
        $days = array_map($formatDay, $bookingDays);

        // Tableau des chaines de caractères des journées impossibles
        $notAvailable = array_map($formatDay, $notAvailableDays);

        foreach ($days as $day) {
            if (array_search($day, $notAvailable) !== false) {
                return false;
            }
        }

        return true;
    }

    // Récupérer un tableau des journées qui correspondent à ma réservation
    public function getDays(): array
    {
        // Calculer les jours qui se trouvent entre la date d'arrivée et de départ
        $resultat = range(
            $this->startDate->getTimestamp(),
            $this->createdAt->getTimestamp(),
            24 * 60 * 60 
        );

        // Transformer les timestamps en objets DateTime
        // array_map() permet de transformer chaque élément d'un tableau
        $days = array_map(function ($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->reservationDate;
    }

    public function setReservationDate(\DateTimeInterface $reservationDate): self
    {
        $this->reservationDate = $reservationDate;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
