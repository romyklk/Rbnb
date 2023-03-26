<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['title'], message: "Une autre annonce possède déjà ce titre, merci de le modifier")]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: AdRepository::class)]
class Ad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, max: 150, minMessage: "Le titre doit faire au moins 10 caractères", maxMessage: "Le titre ne peut pas faire plus de 150 caractères")]
    #[Assert\NotBlank(message: "Le titre ne peut pas être vide")]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le prix doit être supérieur à 0")]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide")]
    private ?float $Price = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 100, minMessage: "L'introduction doit faire au moins 100 caractères")]
    private ?string $introduction = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(min: 200, minMessage: "Le contenu doit faire au moins 200 caractères")]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    private ?string $coverImage = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Le nombre de pièces doit être supérieur à 0")]
    #[Assert\NotBlank(message: "Le nombre de pièces ne peut pas être vide")]
    private ?int $rooms = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le type ne peut pas être vide")]
    private ?string $type = null;

    private ?string $imagesUrl = null;

    // Permet d'avoir un tableau de jours qui ne sont pas disponibles pour cette annonce de datetime à datetime
//     public function getNotAvailableDays()
//     {
//         $notAvailableDays = [];

//         // Parcourir les réservations de cette annonce
//         foreach ($this->getBookings() as $booking) {
//             // Calculer les jours qui se trouvent entre la date d'arrivée et de départ
//             // range() est une fonction PHP qui permet de générer un tableau de valeurs entre deux valeurs données
//           $resultat = range(
//                 $booking->getStartDate()->getTimestamp(),
//                 $booking->getCreatedAt()->getTimestamp(),
//                 24 * 60 * 60 
//             );
            
//         //!\\ Ce code est à revoir car il ne prend pas en compte les réservations qui ont été créées avant la date de départ de la réservation en cours de traitement dans la boucle foreach ! Pour résoudre ce problème, on va ajouter une condition if qui va vérifier si la date de départ est supérieure à la date de création de la réservation en cours de traitement dans la boucle foreach. Si c'est le cas, on ajoute les jours entre la date de départ et la date de création de la réservation au tableau des jours d'indisponibilité de l'annonce sinon on continue la boucle sans rien ajouter au tableau des jours d'indisponibilité de l'annonce et on passe à la réservation suivante

// // Si la date de départ est supérieure à la date de création de la réservation alors on ajoute les jours entre la date de départ et la date de création de la réservation au tableau des jours d'indisponibilité de l'annonce sinon on continue la boucle sans rien ajouter au tableau des jours d'indisponibilité de l'annonce et on passe à la réservation suivante
// /*             if ((abs($booking->getStartDate()->getTimestamp() - $booking->getCreatedAt()->getTimestamp() >= 24 * 60 * 60))) {
//                 $resultat = range(
//                     $booking->getStartDate()->getTimestamp(),
//                     $booking->getCreatedAt()->getTimestamp(),
//                     24 * 60 * 60
//                 );
//             } else {
//                 continue;
//             }
//  */
//             // Transformer ces timestamps en objets DateTime et les ajouter au tableau des jours d'indisponibilité
//             // array_map() est une fonction PHP qui permet de transformer un tableau
//             $days = array_map(function ($dayTimestamp) {
//                 return new \DateTime(date('Y-m-d', $dayTimestamp));
//             }, $resultat);

//             // array_merge() est une fonction PHP qui permet de fusionner deux tableaux
//             // array_merge() fusionne les tableaux $notAvailableDays et $days
//             // array_merge() prend en premier argument le tableau dans lequel on veut fusionner les autres tableaux
//             // array_merge() prend en second argument le premier tableau à fusionner
//             $notAvailableDays = array_merge($notAvailableDays, $days);
//         }

//         return $notAvailableDays;
//     }

public function getNotAvailableDays()
{
    $notAvailableDays = [];

    foreach ($this->getBookings() as $booking) {
        $startDate = $booking->getStartDate();
        $endDate = $booking->getCreatedAt();
        $interval = new \DateInterval('P1D'); // un intervalle de 1 jour
        $period = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($period as $date) {
            $notAvailableDays[] = $date;
        }
    }

    return $notAvailableDays;
}


    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Image::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    #[Assert\Length(min: 1, max: 255, minMessage: "L'adresse doit faire au moins 10 caractères", maxMessage: "L'adresse ne peut pas faire plus de 255 caractères")]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville ne peut pas être vide")]
    #[Assert\Length(min: 1, max: 255, minMessage: "La ville doit faire au moins 10 caractères", maxMessage: "La ville ne peut pas faire plus de 255 caractères")]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le code postal ne peut pas être vide")]
    #[Assert\Regex(pattern: "/^[0-9]{5}$/", message: "Le code postal doit être composé de 5 chiffres")]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $Country = null;

    #[ORM\ManyToOne(inversedBy: 'ads')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Booking::class)]
    private Collection $bookings;

    #[ORM\OneToMany(mappedBy: 'ad', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->bookings = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    #[ORM\PrePersist] // Pour que cette méthode soit appelée avant la persistance
    #[ORM\PreUpdate] // Pour que cette méthode soit appelée avant la mise à jour
    public function initializeSlugAndCreated(): void
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }

        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    // cette méthode permet de calculer la moyenne des notes de l'annonce
    public function getAvgRatings(): float
    {
        // Calculer la somme des notations
        // $this->comments->toArray() permet de transformer la collection en tableau
        // array_reduce() est une fonction PHP qui permet de transformer un tableau en une valeur unique
        $sum = array_reduce($this->comments->toArray(), function ($total, $comment) {
            return $total + $comment->getRating();
        }, 0); // 0 est la valeur de départ de $total Si l'anonce n'a pas de commentaire, $total vaudra 0

        // Faire la division pour avoir la moyenne
        if (count($this->comments) > 0) {
            return $sum / count($this->comments);
        }

        return 0;
    }

    // Permet de récupérer le commentaire d'un auteur par rapport à une annonce (si il en a déjà laissé un)
    public function getCommentFromAuthor(User $author): ?Comment
    {
        foreach ($this->comments as $comment) {
            if ($comment->getAuthor() === $author) {
                return $comment;
            }
        }

        return null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(string $coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAd($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAd() === $this) {
                $image->setAd(null);
            }
        }

        return $this;
    }




    /**
     * Get the value of imagesUrl
     */
    public function getImagesUrl()
    {
        return $this->imagesUrl;
    }

    /**
     * Set the value of imagesUrl
     *
     * @return  self
     */
    public function setImagesUrl($imagesUrl)
    {
        $this->imagesUrl = $imagesUrl;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): self
    {
        $this->Country = $Country;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setAd($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getAd() === $this) {
                $booking->setAd(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAd($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAd() === $this) {
                $comment->setAd(null);
            }
        }

        return $this;
    }
}
