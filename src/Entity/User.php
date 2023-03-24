<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Email(message: "L'email '{{ value }}' n'est pas valide")]
    #[Assert\NotBlank(message: "L'email ne peut pas être vide")]
    #[Assert\Length(max: 100, maxMessage: "L'email ne peut pas faire plus de 180 caractères")]
    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(min: 8, minMessage: "Le mot de passe doit faire au moins 8 caractères")]
    #[Assert\NotBlank(message: "Le mot de passe ne peut pas être vide")]
    #[Assert\Regex(pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).{8,}$/', message: "Le mot de passe doit contenir au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 100, minMessage: "Le prénom doit faire au moins 2 caractères", maxMessage: "Le prénom ne peut pas faire plus de 100 caractères")]
    #[Assert\NotBlank(message: "Le prénom ne peut pas être vide")]
    #[Assert\Regex(pattern: '/^[a-zA-ZÀ-ÿ]+([-\'\s][a-zA-ZÀ-ÿ]+)*$/', message: "Le prénom n'est pas valide")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 100, minMessage: "Le nom doit faire au moins 2 caractères", maxMessage: "Le nom ne peut pas faire plus de 100 caractères")]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide")]
    #[Assert\Regex(pattern: '/^[a-zA-ZÀ-ÿ]+([-\'\s][a-zA-ZÀ-ÿ]+)*$/', message: "Le nom n'est pas valide")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 50, max: 255, minMessage: "L'introduction doit faire au moins 50 caractères", maxMessage: "L'introduction ne peut pas faire plus de 255 caractères")]
    #[Assert\NotBlank(message: "L'introduction ne peut pas être vide")]
    private ?string $introduction = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La présentation ne peut pas être vide")]
    #[Assert\Length(min: 100, minMessage: "La présentation doit faire au moins 100 caractères")]
    private ?string $presentation = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 200, minMessage: "L'adresse doit faire au moins 2 caractères", maxMessage: "L'adresse ne peut pas faire plus de 200 caractères")]
    #[Assert\NotBlank(message: "L'adresse ne peut pas être vide")]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 100, minMessage: "La ville doit faire au moins 2 caractères", maxMessage: "La ville ne peut pas faire plus de 100 caractères")]
    #[Assert\NotBlank(message: "La ville ne peut pas être vide")]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La ville ne peut pas être vide")]
    #[Assert\Length(min: 5, max: 5, exactMessage: "Le code postal doit faire 5 caractères")]
    #[Assert\Regex(pattern: '/^[0-9]{5}$/', message: "Le code postal n'est pas valide")]
    private ?string $postalCode = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilPicture = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, max: 10, exactMessage: "Le numéro de téléphone doit faire 10 caractères")]
    #[Assert\Regex(pattern: '/^[0-9]{10}$/', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être vide")]
    #[Assert\NotEqualTo(value: '0000000000', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '1111111111', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '2222222222', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '3333333333', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '4444444444', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '5555555555', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '6666666666', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '7777777777', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '8888888888', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '9999999999', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '1234567890', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '0987654321', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '0123456789', message: "Le numéro de téléphone n'est pas valide")]
    #[Assert\NotEqualTo(value: '9876543210', message: "Le numéro de téléphone n'est pas valide")]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Ad::class)]
    private Collection $ads;

    public function __construct()
    {
        $this->ads = new ArrayCollection();
    }


    #[ORM\PrePersist] // Pour que cette méthode soit appelée avant la persistance
    #[ORM\PreUpdate] // Pour que cette méthode soit appelée avant la mise à jour
    public function initializeSlugAndCreated(): void
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName . '-' . $this->lastName);
        }

        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

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

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getProfilPicture(): ?string
    {
        return $this->profilPicture;
    }

    public function setProfilPicture(?string $profilPicture): self
    {
        $this->profilPicture = $profilPicture;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Ad>
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads->add($ad);
            $ad->setAuthor($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getAuthor() === $this) {
                $ad->setAuthor(null);
            }
        }

        return $this;
    }
}
