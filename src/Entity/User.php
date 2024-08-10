<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface /*, /*TwoFactorInterface*/
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Password = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleAuthenticatorSecret;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'user')]
    private Collection $tikets;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $google_id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

        public function __construct() {
            $this->createdAt = new \DateTimeImmutable();
            $this->roles[]="user";
            $this->tikets = new ArrayCollection();
        }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getSalt(): ?string
    {
        return null; // Utilisez un encodeur sans sel ou gérez le sel directement dans l'encodeur
    }
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Effacez les informations sensibles ici si nécessaire
    }


    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(?string $Password): static
    {
        $this->Password = $Password;

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

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->Email;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTikets(): Collection
    {
        return $this->tikets;
    }

    public function addTiket(Ticket $tiket): static
    {
        if (!$this->tikets->contains($tiket)) {
            $this->tikets->add($tiket);
            $tiket->setUser($this);
        }

        return $this;
    }

    public function removeTiket(Ticket $tiket): static
    {
        if ($this->tikets->removeElement($tiket)) {
            // set the owning side to null (unless already changed)
            if ($tiket->getUser() === $this) {
                $tiket->setUser(null);
            }
        }

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->google_id;
    }

    public function setGoogleId(?string $google_id): static
    {
        $this->google_id = $google_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

        
}
